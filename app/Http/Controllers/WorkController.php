<?php

namespace App\Http\Controllers;

use App\Exports\WorkExport;
use App\Mail\WorkCreatedMail;
use App\Models\User;
use App\Models\Work;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class WorkController extends Controller
{
    public const MAX_WORKS_PER_USER = 3;
    public const MAX_ABSTRACT_WORDS = 700;

    public const AREAS = [
        'RINOPLASTIA',
        'CONTORNO CORPORAL',
        'MAMA',
        'CIRUGÍA RECONSTRUCTIVA',
        'FISURA LABIO PALATINA',
        'PROCEDIMIENTOS MINIMAMENTE INVASIVOS',
        'QUEMADOS',
        'OTROS',
    ];

    public const STATUSES = ['Enviado', 'En Revisión', 'Aceptado', 'Rechazado'];

    public function index()
    {
        abort_unless(auth()->user()->can('works.index'), 403);

        $query = Work::query()->latest('id');
        if (!auth()->user()->hasAnyRole(['Administrador', 'Secretaria'])) {
            $query->where('user_id', auth()->id());
        }

        $registeredWorksCount = Work::where('user_id', auth()->id())->count();

        return view('pages.works.index', [
            'works' => $query->get(),
            'registeredWorksCount' => $registeredWorksCount,
            'canRegisterMoreWorks' => $registeredWorksCount < self::MAX_WORKS_PER_USER,
            'maxWorksPerUser' => self::MAX_WORKS_PER_USER,
        ])->with($this->pageData('works'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('works.create'), 403);

        if (Work::where('user_id', auth()->id())->count() >= self::MAX_WORKS_PER_USER) {
            return redirect()->route('works.index')
                ->with('error', 'Solo puedes registrar un máximo de '.self::MAX_WORKS_PER_USER.' trabajos.');
        }

        return view('pages.works.create', [
            'user' => User::findOrFail(auth()->id()),
            'areas' => self::AREAS,
            'maxAbstractWords' => self::MAX_ABSTRACT_WORDS,
        ])->with($this->pageData('works_create'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->can('works.create'), 403);

        $data = $this->validatedData($request, $request->user());
        $work = DB::transaction(function () use ($data, $request) {
            User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();

            if (Work::where('user_id', $request->user()->id)->count() >= self::MAX_WORKS_PER_USER) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'limit' => 'Solo puedes registrar un máximo de '.self::MAX_WORKS_PER_USER.' trabajos.',
                ]);
            }

            $data['user_id'] = $request->user()->id;
            $data['status'] = 'Enviado';

            return Work::create($data);
        });

        try {
            $mail = Mail::to($work->email);
            $notificationEmail = config('services.correonotificacion.trabajo');

            if (filled($notificationEmail)) {
                $mail->cc($notificationEmail);
            }

            $mail->send(new WorkCreatedMail($work));
        } catch (\Throwable $exception) {
            Log::warning('El trabajo fue registrado, pero no se pudo enviar el correo.', [
                'work_id' => $work->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return redirect()->route('works.index')->with('success', 'Trabajo registrado correctamente.');
    }

    public function show($id)
    {
        $work = Work::findOrFail($id);
        $this->ensureCanAccess($work);

        if (auth()->user()->hasRole('Administrador')) {
            $work->load('statusNotes.user');
        }

        $statuses = self::STATUSES;

        return view('pages.works.show', compact('work', 'statuses'))->with($this->pageData('works_show'));
    }

    public function edit($id)
    {
        $work = Work::findOrFail($id);
        $this->ensureCanManage($work);

        return view('pages.works.edit', [
            'work' => $work,
            'areas' => self::AREAS,
            'maxAbstractWords' => self::MAX_ABSTRACT_WORDS,
        ])->with($this->pageData('works_edit'));
    }

    public function update(Request $request, $id)
    {
        $work = Work::findOrFail($id);
        $this->ensureCanManage($work);
        $work->update($this->validatedData($request, $work->user));

        return redirect()->route('works.show', $work)->with('success', 'Trabajo actualizado correctamente.');
    }

    public function updateStatus(Request $request, Work $work)
    {
        abort_unless($request->user()->hasRole('Administrador'), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
            'note' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'status' => 'estado',
            'note' => 'nota',
        ]);

        if ($work->status === $validated['status']) {
            return back()->with('error', 'Selecciona un estado diferente al actual.')->withInput();
        }

        DB::transaction(function () use ($work, $validated, $request) {
            $previousStatus = $work->status;
            $work->update(['status' => $validated['status']]);
            $work->statusNotes()->create([
                'action' => "Cambió de '{$previousStatus}' a '{$validated['status']}'",
                'note' => filled($validated['note'] ?? null) ? trim($validated['note']) : 'Sin nota.',
                'user_id' => $request->user()->id,
            ]);
        });

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function exportExcelWorks()
    {
        abort_unless(auth()->user()->hasAnyRole(['Administrador', 'Secretaria']), 403);

        return Excel::download(new WorkExport, 'works-'.now()->format('Y-m-d_H-i-s').'.xlsx');
    }

    private function validatedData(Request $request, User $owner): array
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:180'],
            'whatsapp_phone' => ['required', new PhoneNumber],
            'current_position' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'country' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
            'area' => ['required', Rule::in(self::AREAS)],
            'area_other' => ['nullable', 'required_if:area,OTROS', 'string', 'max:255'],
            'abstract' => ['required', 'string'],
        ], [
            'area_other.required_if' => 'Especifica el área del trabajo.',
        ], [
            'first_name' => 'nombre',
            'last_name' => 'apellidos',
            'whatsapp_phone' => 'teléfono de WhatsApp',
            'current_position' => 'cargo o posición actual',
            'institution' => 'institución',
            'city' => 'ciudad',
            'country' => 'país',
            'title' => 'título del trabajo',
            'area' => 'área',
            'area_other' => 'otra área',
            'abstract' => 'resumen',
        ]);

        $validator->after(function ($validator) use ($request) {
            $text = trim(strip_tags((string) $request->input('abstract')));
            $words = $text === '' ? 0 : count(preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY));
            if ($words > self::MAX_ABSTRACT_WORDS) {
                $validator->errors()->add('abstract', 'El resumen no puede superar las '.self::MAX_ABSTRACT_WORDS." palabras. Actualmente contiene {$words}.");
            }
        });

        $data = $validator->validate();
        $data['first_name'] = mb_strtoupper(trim($data['first_name']), 'UTF-8');
        $data['last_name'] = mb_strtoupper(trim($data['last_name']), 'UTF-8');
        $data['email'] = mb_strtolower(trim((string) $owner->email), 'UTF-8');
        $data['whatsapp_phone'] = trim($data['whatsapp_phone']);
        $data['area_other'] = $data['area'] === 'OTROS' ? trim((string) $data['area_other']) : null;

        return $data;
    }

    private function ensureCanAccess(Work $work): void
    {
        abort_unless(
            (int) $work->user_id === (int) auth()->id()
            || auth()->user()->hasAnyRole(['Administrador', 'Secretaria']),
            403
        );
    }

    private function ensureCanManage(Work $work): void
    {
        abort_unless(auth()->user()->hasRole('Administrador'), 403);
    }

    private function pageData(string $pageName): array
    {
        return [
            'category_name' => 'works',
            'page_name' => $pageName,
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];
    }
}
