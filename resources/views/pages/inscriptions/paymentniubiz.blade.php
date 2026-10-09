@extends('layouts.app')

@section('content')
<style>
    .niubiz-payment-card { max-width: 720px; margin: 0 auto; border: 1px solid #e5e9f2; border-radius: 16px; overflow: hidden; }
    .niubiz-summary { background: #f7f8fc; border-radius: 12px; padding: 1rem; }
    .niubiz-summary-row { display: flex; justify-content: space-between; gap: 1rem; padding: .4rem 0; }
    .niubiz-total { font-size: 1.45rem; color: #4361ee; }
    .niubiz-security { display: flex; align-items: center; justify-content: center; gap: .45rem; color: #667085; font-size: .82rem; }
    @media (max-width: 575.98px) {
        .niubiz-payment-card .card-body { padding: 1rem !important; }
        .niubiz-summary-row { display: block; }
        .niubiz-summary-row span:last-child { display: block; margin-top: .1rem; }
    }
</style>

<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <div class="row layout-spacing">
            <div class="col-12 layout-top-spacing mt-4">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card niubiz-payment-card">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <span class="badge badge-light-primary mb-2">Pago con tarjeta</span>
                            <h4 class="mb-1">Completa el pago de tu inscripción</h4>
                            <p class="text-muted mb-0">El formulario seguro será procesado por Niubiz.</p>
                        </div>

                        <div class="niubiz-summary mb-3">
                            <div class="niubiz-summary-row">
                                <span class="text-muted">Concepto</span>
                                <span class="fw-semibold text-end">Inscripción · {{ $datainscription->category_inscription_name }}</span>
                            </div>
                            <div class="niubiz-summary-row">
                                <span class="text-muted">Pedido</span>
                                <span class="fw-semibold">{{ $purchaseNumber }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="niubiz-summary-row align-items-center">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold niubiz-total">US$ {{ number_format((float) $amount, 2) }}</span>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmPay">
                            <label class="form-check-label" for="confirmPay">
                                Confirmo que soy mayor de 18 años y acepto los <a href="https://bolivarianofilacpcusco2027.org/inscripciones/" target="_blank" rel="noopener noreferrer">Términos y Condiciones</a> y la <a href="https://bolivarianofilacpcusco2027.org/inscripciones/" target="_blank" rel="noopener noreferrer">Política de Privacidad</a>.
                            </label>
                        </div>

                        <button type="button" class="btn btn-primary btn-lg w-100" id="btnpago" disabled>
                            Pagar US$ {{ number_format((float) $amount, 2) }}
                        </button>
                        <a href="{{ route('inscriptions.show', $datainscription->id) }}" class="btn btn-link w-100 mt-1">Pagar después</a>

                        <div class="niubiz-security mt-3">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <span>Pago cifrado y procesado por Niubiz</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ config('services.niubiz.url_js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const confirmPay = document.getElementById('confirmPay');
    const paymentButton = document.getElementById('btnpago');

    if (typeof window.VisanetCheckout === 'undefined') {
        paymentButton.disabled = true;
        paymentButton.textContent = 'No se pudo cargar Niubiz';
        return;
    }

    VisanetCheckout.configure({
        sessiontoken: @json($sessionToken),
        channel: 'web',
        merchantid: @json((string) config('services.niubiz.merchant_id')),
        purchasenumber: {{ (int) $purchaseNumber }},
        amount: {{ json_encode((float) $amount) }},
        expirationminutes: '20',
        timeouturl: @json(route('inscriptions.paymentniubiz', $datainscription->id)),
        merchantlogo: @json(asset('assets/img/logo-radla-niubiz.png')),
        formbuttoncolor: '#4361ee',
        action: @json(route('inscriptions.confirmpaymentniubiz', $datainscription->id)),
        complete: function () {}
    });

    confirmPay.addEventListener('change', function () {
        paymentButton.disabled = !this.checked;
    });
    paymentButton.addEventListener('click', function () {
        if (!confirmPay.checked) return;
        VisanetCheckout.open();
    });
});
</script>
@endsection
