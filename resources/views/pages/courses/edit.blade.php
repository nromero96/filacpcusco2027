@extends('layouts.app')
@section('content')
<div class="layout-px-spacing"><div class="middle-content container-xxl p-0"><div class="row layout-spacing"><div class="col-12 layout-top-spacing mt-4">
    <div class="statbox widget box box-shadow">
        <div class="widget-header"><div class="row"><div class="col-12"><h4>Editar curso</h4></div></div></div>
        <div class="widget-content widget-content-area">
            <form method="POST" action="{{ route('courses.update', $course) }}">@csrf @method('PUT')
                @include('pages.courses._form')
                <div class="text-end mt-4"><a href="{{ route('courses.index') }}" class="btn btn-secondary">Cancelar</a> <button class="btn btn-primary">Actualizar curso</button></div>
            </form>
        </div>
    </div>
</div></div></div></div>
@endsection
