@extends('layouts.app') @section('content')
<div class="layout-px-spacing"><div class="middle-content container-xxl p-0"><div class="row layout-spacing"><div class="col-12 layout-top-spacing mt-4"><div class="statbox widget box box-shadow"><div class="widget-header px-3"><div class="row"><div class="col"><h4 class="px-0">Compradores: {{$tour->name}}</h4></div><div class="col-auto"><a class="btn btn-secondary" href="{{route('tours.index')}}">Volver</a></div></div></div><div class="widget-content widget-content-area"><div class="table-responsive"><table class="table"><thead><tr><th>Inscripción</th><th>Participante</th><th>Precio</th><th>Acompañante</th><th>Total tour</th></tr></thead><tbody>
@forelse($buyers as $buyer)
<tr><td><a href="{{route('inscriptions.show',$buyer->id)}}">#{{$buyer->id}}</a></td><td>{{$buyer->user_name}} {{$buyer->user_lastname}}<br><small>{{$buyer->user_email}}</small></td><td>US$ {{number_format($buyer->pivot->unit_price,2)}}</td><td>@if($buyer->pivot->has_accompanist) <b>{{$buyer->pivot->accompanist_name}}</b><br>{{$buyer->pivot->accompanist_document_type}} {{$buyer->pivot->accompanist_document_number}}<br>{{$buyer->pivot->accompanist_phone}} @else No @endif</td><td>US$ {{number_format($buyer->pivot->unit_price+$buyer->pivot->accompanist_price,2)}}</td></tr>
@empty
<tr><td colspan="5" class="text-center">No hay compradores.</td></tr>
@endforelse
</tbody></table></div>{{$buyers->links()}}</div></div></div></div></div></div>
@endsection
