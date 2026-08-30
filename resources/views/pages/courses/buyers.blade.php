@extends('layouts.app')
@section('content')
<div class="layout-px-spacing"><div class="middle-content container-xxl p-0"><div class="row layout-spacing"><div class="col-12 layout-top-spacing mt-4">
    <div class="statbox widget box box-shadow">
        <div class="widget-header px-3"><div class="row align-items-center"><div class="col"><h4 class="px-0">Compradores: {{ $course->name }}</h4></div><div class="col-auto"><a href="{{ route('courses.index') }}" class="btn btn-secondary">Volver</a></div></div></div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Inscripción</th><th>Participante</th><th>Documento</th><th>Contacto</th><th>Precio pagado</th><th>Estado inscripción</th><th>Fecha compra</th></tr></thead><tbody>
            @forelse($buyers as $buyer)<tr>
                <td><a href="{{ route('inscriptions.show', $buyer->id) }}">#{{ $buyer->id }}</a></td>
                <td>{{ trim($buyer->user_name.' '.$buyer->user_lastname.' '.$buyer->user_second_lastname) }}</td>
                <td>{{ $buyer->user_document_number }}</td><td>{{ $buyer->user_email }}<br><small>{{ $buyer->user_phone_number }}</small></td>
                <td>US$ {{ number_format($buyer->pivot->unit_price, 2) }}</td><td>{{ $buyer->status }}</td><td>{{ $buyer->pivot->created_at }}</td>
            </tr>@empty<tr><td colspan="7" class="text-center py-4">Este curso todavía no tiene compradores.</td></tr>@endforelse
            </tbody></table></div>{{ $buyers->links() }}
        </div>
    </div>
</div></div></div></div>
@endsection
