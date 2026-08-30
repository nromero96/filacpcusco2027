@extends('layouts.app') @section('content')
<div class="layout-px-spacing"><div class="middle-content container-xxl p-0"><div class="row layout-spacing"><div class="col-12 layout-top-spacing mt-4">@if(session('success'))<div class="alert alert-success">{{session('success')}}</div>@endif<div class="statbox widget box box-shadow"><div class="widget-header px-3"><div class="row align-items-center"><div class="col"><h4 class="px-0">Tours</h4></div><div class="col-auto"><a class="btn btn-primary" href="{{route('tours.create')}}">Nuevo tour</a></div></div></div><div class="widget-content widget-content-area"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Tour</th><th>Fecha</th><th>Precios</th><th>Cupos/compras</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
@forelse($tours as $tour)
<tr><td><b>{{$tour->name}}</b><br><small>{{$tour->meeting_point}}</small></td><td>{{$tour->tour_date?$tour->tour_date->format('d/m/Y'):'Por definir'}}</td><td>Participante: US$ {{number_format($tour->price,2)}}<br>Acompañante: US$ {{number_format($tour->accompanist_price,2)}}</td><td>{{$tour->inscriptions_count}} compras · {{$tour->sold_seats}} lugares @if($tour->capacity) / {{$tour->capacity}} cupos @endif</td><td>{{$tour->status==='active'?'Activo':'Inactivo'}}</td><td><a class="btn btn-sm btn-info" href="{{route('tours.buyers',$tour)}}">Compradores</a> <a class="btn btn-sm btn-primary" href="{{route('tours.edit',$tour)}}">Editar</a></td></tr>
@empty
<tr><td colspan="6" class="text-center">No hay tours registrados.</td></tr>
@endforelse
</tbody></table></div>{{$tours->links()}}</div></div></div></div></div></div>
@endsection
