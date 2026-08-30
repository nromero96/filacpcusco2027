<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()->hasRole('Administrador'), 403);
    }

    private function viewData(string $page): array
    {
        return [
            'category_name' => 'courses',
            'page_name' => $page,
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];
    }

    public function index()
    {
        $this->authorizeAdmin();
        $courses = Course::withCount('inscriptions')->orderBy('course_date')->orderBy('name')->paginate(20);
        return view('pages.courses.index', compact('courses'))->with($this->viewData('courses'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('pages.courses.create')->with($this->viewData('courses_create'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        Course::create($this->validated($request));
        return redirect()->route('courses.index')->with('success', 'Curso creado correctamente.');
    }

    public function edit(Course $course)
    {
        $this->authorizeAdmin();
        return view('pages.courses.edit', compact('course'))->with($this->viewData('courses_edit'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeAdmin();
        $validated = $this->validated($request);
        $buyersCount = $course->inscriptions()->count();
        if ($validated['capacity'] !== null && $validated['capacity'] < $buyersCount) {
            return back()->withErrors(['capacity' => "El curso ya tiene {$buyersCount} compras; el cupo no puede ser menor."])->withInput();
        }
        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Curso actualizado correctamente.');
    }

    public function buyers(Course $course)
    {
        $this->authorizeAdmin();
        $buyers = $course->inscriptions()
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->select('inscriptions.*', 'users.name as user_name', 'users.lastname as user_lastname',
                'users.second_lastname as user_second_lastname', 'users.email as user_email',
                'users.document_number as user_document_number', 'users.phone_number as user_phone_number')
            ->orderByDesc('course_inscription.created_at')
            ->paginate(30);

        return view('pages.courses.buyers', compact('course', 'buyers'))->with($this->viewData('courses_buyers'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'course_date' => 'nullable|date',
            'start_time' => 'nullable|required_with:end_time|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'capacity' => 'nullable|integer|min:1|max:100000',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
