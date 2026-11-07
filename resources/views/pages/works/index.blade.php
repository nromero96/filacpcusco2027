@extends('layouts.app')

@section('content')
<style>
    .work-list-status { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .65rem; border:1px solid transparent; border-radius:999px; font-size:.75rem; font-weight:700; white-space:nowrap; }
    .work-list-status::before { content:''; width:7px; height:7px; border-radius:50%; background:currentColor; }
    .work-list-status.status-enviado { color:#2563eb; background:#eff6ff; border-color:#bfdbfe; }
    .work-list-status.status-revision { color:#b45309; background:#fff7ed; border-color:#fed7aa; }
    .work-list-status.status-aceptado { color:#15803d; background:#f0fdf4; border-color:#bbf7d0; }
    .work-list-status.status-rechazado { color:#b91c1c; background:#fef2f2; border-color:#fecaca; }
</style>
<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <div class="row layout-spacing">
            <div class="col-12 layout-top-spacing layout-spacing">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                    <div>
                        <h4 class="mb-1">Trabajos registrados</h4>
                        <p class="text-muted mb-0">Temas libres enviados para el evento.</p>
                    </div>
                    <div class="d-flex gap-2">
                        @if(Auth::user()->hasAnyRole(['Administrador', 'Secretaria']))
                            <a href="{{ route('works.exportexcel') }}" class="btn btn-outline-success">Exportar Excel</a>
                        @endif
                        @can('works.create')
                            @if($canRegisterMoreWorks)
                                <a href="{{ route('works.create') }}" class="btn btn-primary">Nuevo trabajo</a>
                            @else
                                <button type="button" class="btn btn-secondary" disabled title="Máximo {{ $maxWorksPerUser }} trabajos por usuario">
                                    Límite alcanzado ({{ $registeredWorksCount }}/{{ $maxWorksPerUser }})
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>

                <div class="widget-content widget-content-area p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="work-list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre y apellidos</th>
                                    <th>Ciudad / País</th>
                                    <th>Área</th>
                                    <th>Título</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($works as $work)
                                    <tr>
                                        <td>
                                            <a href="{{ route('works.show', $work) }}" class="fw-bold text-primary" aria-label="Ver trabajo número {{ $work->id }}">
                                                #{{ $work->id }}
                                            </a>
                                        </td>
                                        <td><strong>{{ $work->first_name }} {{ $work->last_name }}</strong><br><small>{{ $work->email }}</small></td>

                                        <td>{{ $work->city }} / {{ $work->country }}</td>
                                        <td>{{ $work->area === 'OTROS' ? $work->area_other : $work->area }}</td>
                                        <td style="min-width:240px; white-space:normal;">{{ $work->title }}</td>
                                        @php
                                            $rowStatusClass = [
                                                'Enviado' => 'status-enviado',
                                                'En Revisión' => 'status-revision',
                                                'Aceptado' => 'status-aceptado',
                                                'Rechazado' => 'status-rechazado',
                                            ][$work->status] ?? 'status-enviado';
                                        @endphp
                                        <td><span class="work-list-status {{ $rowStatusClass }}">{{ $work->status }}</span></td>
                                        <td class="text-center text-nowrap">
                                            <a href="{{ route('works.show', $work) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                            @if(Auth::user()->hasRole('Administrador'))
                                                <a href="{{ route('works.edit', $work) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <h6>No hay trabajos registrados.</h6>
                                            @can('works.create')
                                                @if($canRegisterMoreWorks)
                                                    <a href="{{ route('works.create') }}" class="btn btn-primary mt-2">Registrar el primer trabajo</a>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
