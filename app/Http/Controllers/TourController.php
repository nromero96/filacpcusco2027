<?php
namespace App\Http\Controllers;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class TourController extends Controller {
    private function admin(){ abort_unless(auth()->user()->hasRole('Administrador'),403); }
    private function data($page){ return ['category_name'=>'tours','page_name'=>$page,'has_scrollspy'=>0,'scrollspy_offset'=>'']; }
    public function index(){ $this->admin(); $tours=Tour::withCount('inscriptions')->orderBy('tour_date')->orderBy('name')->paginate(20); $tours->getCollection()->each(fn($tour)=>$tour->sold_seats=$tour->inscriptions()->get()->sum(fn($i)=>1+(int)$i->pivot->has_accompanist)); return view('pages.tours.index',compact('tours'))->with($this->data('tours')); }
    public function create(){ $this->admin(); return view('pages.tours.create')->with($this->data('tours_create')); }
    public function store(Request $request){ $this->admin(); Tour::create($this->validated($request)); return redirect()->route('tours.index')->with('success','Tour creado correctamente.'); }
    public function edit(Tour $tour){ $this->admin(); return view('pages.tours.edit',compact('tour'))->with($this->data('tours_edit')); }
    public function update(Request $request,Tour $tour){ $this->admin(); $data=$this->validated($request); $seats=$tour->inscriptions()->get()->sum(fn($i)=>1+(int)$i->pivot->has_accompanist); if($data['capacity']!==null && $data['capacity']<$seats) return back()->withErrors(['capacity'=>"El tour ya tiene {$seats} lugares vendidos; el cupo no puede ser menor."])->withInput(); $tour->update($data); return redirect()->route('tours.index')->with('success','Tour actualizado correctamente.'); }
    public function buyers(Tour $tour){ $this->admin(); $buyers=$tour->inscriptions()->join('users','inscriptions.user_id','=','users.id')->select('inscriptions.*','users.name as user_name','users.lastname as user_lastname','users.email as user_email','users.document_number as user_document_number')->orderByDesc('inscription_tour.created_at')->paginate(30); return view('pages.tours.buyers',compact('tour','buyers'))->with($this->data('tours_buyers')); }
    private function validated(Request $request){ return $request->validate(['name'=>'required|string|max:255','description'=>'nullable|string|max:2000','tour_date'=>'nullable|date','start_time'=>'nullable|required_with:end_time|date_format:H:i','end_time'=>'nullable|date_format:H:i|after:start_time','meeting_point'=>'nullable|string|max:255','price'=>'required|numeric|min:0|max:999999.99','accompanist_price'=>'required|numeric|min:0|max:999999.99','capacity'=>'nullable|integer|min:1|max:100000','status'=>['required',Rule::in(['active','inactive'])]]); }
}
