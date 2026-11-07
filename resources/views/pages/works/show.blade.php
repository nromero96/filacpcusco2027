@extends('layouts.app')

@section('content')
@php
    $statusClass = [
        'Enviado' => 'status-enviado',
        'En Revisión' => 'status-revision',
        'Aceptado' => 'status-aceptado',
        'Rechazado' => 'status-rechazado',
    ][$work->status] ?? 'status-enviado';
@endphp
<style>
    .work-detail-card { border: 1px solid #e6eaf0; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 28px rgba(31,45,61,.07); }
    .work-detail-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1.15rem 1.25rem; border-top:4px solid #882c31; border-bottom:1px solid #edf0f5; background:linear-gradient(180deg, #fff 0%, #fcf8f8 100%); }
    .work-detail-header-main { display:flex; align-items:center; gap:.8rem; min-width:0; }
    .work-detail-header-icon { width:44px; height:44px; display:inline-flex; align-items:center; justify-content:center; flex:0 0 auto; border-radius:12px; color:#882c31; background:#f4e8e9; }
    .work-detail-header-eyebrow { display:block; margin-bottom:.1rem; color:#9a7c7f; font-size:.72rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; }
    .work-detail-header-title { margin:0; color:#3b1f21; font-size:1.2rem; line-height:1.4; }
    .work-detail-header-actions { display:flex; align-items:center; gap:.6rem; flex:0 0 auto; }
    .work-detail-header-actions .btn { display:inline-flex; align-items:center; padding:.42rem .7rem; border-radius:9px; }
    .work-detail-status { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .75rem; border:1px solid transparent; border-radius:999px; font-size:.78rem; font-weight:700; white-space:nowrap; }
    .work-detail-status::before { content:''; width:8px; height:8px; border-radius:50%; background:currentColor; }
    .work-detail-status.status-enviado { color:#2563eb; background:#eff6ff; border-color:#bfdbfe; }
    .work-detail-status.status-revision { color:#b45309; background:#fff7ed; border-color:#fed7aa; }
    .work-detail-status.status-aceptado { color:#15803d; background:#f0fdf4; border-color:#bbf7d0; }
    .work-detail-status.status-rechazado { color:#b91c1c; background:#fef2f2; border-color:#fecaca; }
    .work-detail-section { padding: 1.25rem; }
    .work-detail-section + .work-detail-section { border-top: 1px solid #edf0f5; }
    .work-detail-label { margin-bottom: .2rem; color: #888ea8; font-size: .76rem; font-weight: 700; letter-spacing: .03em; text-transform: uppercase; }
    .work-detail-value { margin-bottom: 0; color: #1b2559; overflow-wrap: anywhere; }
    .work-abstract { padding: 1rem; border-radius: 12px; background: #f8f9fc; color: #3b3f5c; line-height: 1.65; white-space: pre-line; }
    .work-admin-status-card { border: 1px solid #e6eaf0; border-top: 4px solid #882c31; border-radius: 16px; box-shadow: 0 10px 28px rgba(31,45,61,.07); overflow: hidden; }
    .work-admin-status-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.2rem; background:#fcf8f8; border-bottom:1px solid #edf0f5; }
    .work-admin-status-body { padding:1.2rem; }
    .work-admin-status-card .form-control, .work-admin-status-card .form-select { min-height:44px; border-radius:10px; }
    .work-status-history { margin:0; padding:0; list-style:none; }
    .work-status-history li { position:relative; margin-left:.45rem; padding:0 0 1rem 1.4rem; border-left:2px solid #eadfe0; }
    .work-status-history li:last-child { padding-bottom:0; }
    .work-status-history-dot { position:absolute; left:-7px; top:4px; width:12px; height:12px; border:3px solid #fff; border-radius:50%; background:#882c31; box-shadow:0 0 0 2px #eadfe0; }
    .work-status-history-entry { padding:.75rem .85rem; border:1px solid #edf0f5; border-radius:10px; background:#fafbfc; }
    .work-status-history-meta { display:flex; justify-content:space-between; gap:.75rem; margin-top:.35rem; color:#888ea8; font-size:.75rem; }
    @media (max-width:575.98px) {
        .work-detail-header { align-items:flex-start; flex-direction:column; }
        .work-detail-header-actions { margin-left:52px; flex-wrap:wrap; }
        .work-status-history-meta { flex-direction:column; gap:.15rem; }
    }
    @media print {
        @page { size:A4; margin:12mm; }
        body { background:#fff !important; }
        .navbar, .header-container, .sidebar-wrapper, .footer-wrapper, .work-print-actions, .work-screen-only, .work-admin-status-card { display:none !important; }
        .main-container, #content, .layout-px-spacing, .middle-content, .layout-spacing, .layout-top-spacing { margin:0 !important; padding:0 !important; width:100% !important; max-width:none !important; }
        .work-detail-card { border:1px solid #d8dce2 !important; box-shadow:none !important; break-inside:avoid; }
        .work-detail-header { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        .work-detail-section { break-inside:avoid; }
        .work-abstract { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    }
</style>

<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <div class="row layout-spacing">
            <div class="col-lg-12 layout-top-spacing mt-4">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card work-detail-card">
                    <div class="work-detail-header">
                        <div class="work-detail-header-main">
                            <span class="work-detail-header-icon">
                                <svg width="23" height="23" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6"></path><path d="M8 13h8M8 17h6"></path></svg>
                            </span>
                            <div>
                                <span class="work-detail-header-eyebrow">Trabajo #{{ $work->id }}</span>
                                <h4 class="work-detail-header-title">Detalle del trabajo</h4>
                            </div>
                        </div>
                        <div class="work-detail-header-actions">
                            <span class="work-detail-status {{ $statusClass }}">{{ $work->status }}</span>
                            <button type="button" class="btn btn-outline-primary work-print-actions" onclick="printWorkAsPdf()">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" class="me-1"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><path d="M6 14h12v8H6z"></path></svg>
                                Imprimir
                            </button>
                        </div>
                    </div>

                    <div class="work-detail-section">
                        <h6 class="mb-3">Datos de contacto y actividad profesional</h6>
                        <div class="row g-3">
                            <div class="col-md-6"><div class="work-detail-label">Nombre</div><p class="work-detail-value">{{ $work->first_name }}</p></div>
                            <div class="col-md-6"><div class="work-detail-label">Apellidos</div><p class="work-detail-value">{{ $work->last_name }}</p></div>
                            <div class="col-md-6"><div class="work-detail-label">Correo electrónico</div><p class="work-detail-value">{{ $work->email }}</p></div>
                            <div class="col-md-6"><div class="work-detail-label">WhatsApp</div><p class="work-detail-value">{{ $work->whatsapp_phone }}</p></div>
                            <div class="col-md-6"><div class="work-detail-label">Cargo o posición actual</div><p class="work-detail-value">{{ $work->current_position }}</p></div>
                            <div class="col-12"><div class="work-detail-label">Institución o lugar de actividad profesional</div><p class="work-detail-value">{{ $work->institution }}</p></div>
                            <div class="col-md-6"><div class="work-detail-label">Ciudad</div><p class="work-detail-value">{{ $work->city }}</p></div>
                            <div class="col-md-6"><div class="work-detail-label">País</div><p class="work-detail-value">{{ $work->country }}</p></div>
                        </div>
                    </div>

                    <div class="work-detail-section">
                        <h6 class="mb-3">Información del trabajo</h6>
                        <div class="row g-3">
                            <div class="col-12"><div class="work-detail-label">Título del trabajo</div><p class="work-detail-value">{{ $work->title }}</p></div>
                            <div class="col-md-6"><div class="work-detail-label">Área</div><p class="work-detail-value">{{ $work->area }}</p></div>
                            @if($work->area === 'OTROS')
                                <div class="col-md-6"><div class="work-detail-label">Otra área</div><p class="work-detail-value">{{ $work->area_other }}</p></div>
                            @endif
                        </div>
                    </div>

                    <div class="work-detail-section">
                        <h6 class="mb-3">Resumen del tema libre</h6>
                        <div class="work-abstract">{{ $work->abstract }}</div>
                    </div>

                    <div class="work-detail-section work-screen-only d-flex justify-content-end">
                        <a href="{{ route('works.index') }}" class="btn btn-outline-secondary">Volver al listado</a>
                    </div>
                </div>

                @if(Auth::user()->hasRole('Administrador'))
                    <div class="card work-admin-status-card mt-3">
                        <div class="work-admin-status-header">
                            <div>
                                <h6 class="mb-1">Gestión administrativa</h6>
                                <small class="text-muted">Actualiza el estado y registra una nota en el historial.</small>
                            </div>
                            <span class="work-detail-status {{ $statusClass }}">{{ $work->status }}</span>
                        </div>
                        <div class="work-admin-status-body">
                            @if($errors->any())
                                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                            @endif

                            <form action="{{ route('works.updatestatus', $work) }}" method="POST" class="row g-3 align-items-end">
                                @csrf
                                @method('PUT')
                                <div class="col-md-4">
                                    <label for="status" class="form-label">Estado</label>
                                    <select name="status" id="status" class="form-select" required>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $work->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="note" class="form-label">Nota</label>
                                    <input type="text" name="note" id="note" class="form-control" value="{{ old('note') }}" maxlength="1000" placeholder="Nota opcional">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100" style="min-height:44px;">Actualizar estado</button>
                                </div>
                            </form>

                            <hr class="my-4">
                            <h6 class="mb-3">Historial de cambios</h6>
                            @if($work->statusNotes->isNotEmpty())
                                <ul class="work-status-history">
                                    @foreach($work->statusNotes as $statusNote)
                                        <li>
                                            <span class="work-status-history-dot" aria-hidden="true"></span>
                                            <div class="work-status-history-entry">
                                                <strong>{{ $statusNote->action }}</strong>
                                                <div class="mt-1">{{ $statusNote->note }}</div>
                                                <div class="work-status-history-meta">
                                                    <span>{{ optional($statusNote->user)->name }} {{ optional($statusNote->user)->lastname }}</span>
                                                    <time datetime="{{ $statusNote->created_at->toIso8601String() }}">{{ $statusNote->created_at->format('d/m/Y H:i') }}</time>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0">Todavía no existen cambios de estado registrados.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function printWorkAsPdf() {
    const originalTitle = document.title;
    document.title = @json('TRABAJO-'.$work->id.'-'.$work->first_name.'-'.$work->last_name);
    window.print();
    window.setTimeout(function () {
        document.title = originalTitle;
    }, 500);
}
</script>
@endsection
