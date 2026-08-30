@extends('layouts.app')
@section('content')
<div class="layout-px-spacing"><div class="middle-content container-xxl p-0"><div class="row layout-spacing"><div class="col-12 layout-top-spacing mt-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="statbox widget box box-shadow">
        <div class="widget-header px-3"><div class="row align-items-center"><div class="col"><h4 class="px-0">Cursos</h4></div><div class="col-auto"><a href="{{ route('courses.create') }}" class="btn btn-primary">Nuevo curso</a></div></div></div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"><table class="table table-hover align-middle">
                <thead><tr><th>Curso</th><th>Fecha y horario</th><th>Lugar</th><th>Precio</th><th>Cupos/compras</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
                <tbody>
                @forelse($courses as $course)
                    <tr>
                        <td><strong>{{ $course->name }}</strong><br><small class="text-muted">{{ \Illuminate\Support\Str::limit($course->description, 80) }}</small></td>
                        <td>{{ $course->course_date ? $course->course_date->format('d/m/Y') : 'Por definir' }}<br><small>{{ $course->start_time ? substr($course->start_time, 0, 5) : '' }} @if($course->end_time) - {{ substr($course->end_time, 0, 5) }} @endif</small></td>
                        <td>{{ $course->location ?: 'Por definir' }}</td><td>US$ {{ number_format($course->price, 2) }}</td>
                        <td>
                            {{ $course->inscriptions_count }} /
                            @if($course->capacity)
                                {{ $course->capacity }}
                            @else
                                Sin límite
                            @endif
                        </td>
                        <td><span class="badge {{ $course->status === 'active' ? 'badge-light-success' : 'badge-light-secondary' }}">{{ $course->status === 'active' ? 'Activo' : 'Inactivo' }}</span></td>
                        <td class="text-end"><a href="{{ route('courses.buyers', $course) }}" class="btn btn-sm btn-info">Compradores</a> <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-primary">Editar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">No hay cursos registrados.</td></tr>
                @endforelse
                </tbody>
            </table></div>{{ $courses->links() }}
        </div>
    </div>
</div></div></div></div>
@endsection
