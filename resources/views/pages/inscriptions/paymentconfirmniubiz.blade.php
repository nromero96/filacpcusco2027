@extends('layouts.app')

@section('content')
@php($result = session('niubiz_result'))
<style>
    @media print {
        .payment-result-actions, .navbar, .header-container, .sidebar-wrapper { display: none !important; }
        .layout-px-spacing { padding: 0 !important; }
        .payment-result-card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
</style>
<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <div class="row layout-spacing">
            <div class="col-lg-8 offset-lg-2 layout-top-spacing mt-4">
                <div class="card border-0 box-shadow payment-result-card">
                    <div class="card-body p-4 text-center">
                        @if($result && $result['approved'])
                            <div class="text-success mb-2">
                                <svg width="54" height="54" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="m8 12 3 3 5-6"></path></svg>
                            </div>
                            <h3>Pago realizado correctamente</h3>
                            <p class="text-muted">{{ $result['description'] }}</p>
                        @else
                            <div class="text-danger mb-2">
                                <svg width="54" height="54" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v5"></path><path d="M12 16h.01"></path></svg>
                            </div>
                            <h3>No se pudo completar el pago</h3>
                            <p class="text-muted">{{ $result['description'] ?? 'No recibimos una respuesta válida de Niubiz.' }}</p>
                        @endif

                        @if($result)
                            <div class="bg-light rounded p-3 text-start mx-auto mb-3" style="max-width: 480px;">
                                <div class="d-flex justify-content-between gap-3 mb-2"><span class="text-muted">Inscripción</span><strong>#{{ $result['inscription_id'] }}</strong></div>
                                <div class="d-flex justify-content-between gap-3 mb-2"><span class="text-muted">Pedido</span><strong>{{ $result['purchase_number'] }}</strong></div>
                                @if($result['card'])<div class="d-flex justify-content-between gap-3 mb-2"><span class="text-muted">Tarjeta</span><strong>{{ $result['card'] }} {{ $result['brand'] ? '('.$result['brand'].')' : '' }}</strong></div>@endif
                                <div class="d-flex justify-content-between gap-3"><span class="text-muted">Importe</span><strong>{{ $result['currency'] }} {{ number_format((float) $result['amount'], 2) }}</strong></div>
                            </div>

                            <div class="payment-result-actions d-flex flex-wrap justify-content-center gap-2">
                                @if(!$result['approved'])
                                    <a href="{{ route('inscriptions.paymentniubiz', $result['inscription_id']) }}" class="btn btn-primary">Intentar nuevamente</a>
                                @endif
                                <button type="button" class="btn btn-outline-primary" onclick="window.print()">Imprimir</button>
                                <a href="{{ route('inscriptions.show', $result['inscription_id']) }}" class="btn btn-outline-secondary">Ver inscripción</a>
                            </div>
                        @else
                            <a href="{{ route('inscriptions.index') }}" class="btn btn-primary">Volver a inscripciones</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
