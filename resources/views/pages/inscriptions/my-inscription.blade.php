@extends('layouts.app')


@section('content')

<style>
    .inscription-shell { --inscription-primary: #4361ee; --inscription-soft: #eef2ff; }
    .inscription-intro { background: linear-gradient(135deg, #eef2ff 0%, #f8faff 100%); border: 1px solid #dce4ff; border-radius: 14px; }
    .inscription-step { display: flex; align-items: center; gap: .65rem; color: #344054; font-weight: 700; }
    .inscription-step-number { display: inline-grid; place-items: center; width: 30px; height: 30px; border-radius: 50%; background: var(--inscription-primary); color: #fff; font-size: .85rem; }
    .inscription-shell form.row { --bs-gutter-x: .85rem; --bs-gutter-y: .7rem; }
    .inscription-shell .widget-content-area { padding: 1rem 1.25rem 1.25rem; }
    .inscription-shell .form-label { font-size: .86rem; line-height: 1.25; }
    .inscription-shell .form-control, .inscription-shell .form-select { min-height: 40px; padding-top: .45rem; padding-bottom: .45rem; transition: border-color .2s, box-shadow .2s, background-color .2s; }
    .inscription-shell .form-control:focus, .inscription-shell .form-select:focus { border-color: #7186ee; box-shadow: 0 0 0 .2rem rgba(67, 97, 238, .12); }
    .category-row { cursor: pointer; transition: background-color .2s, box-shadow .2s; }
    .category-row:hover { background: #f8faff; }
    .category-row.is-selected { background: var(--inscription-soft); box-shadow: inset 4px 0 0 var(--inscription-primary); }
    .category-row.is-selected label { color: #263b98; font-weight: 700; }
    .course-card { cursor: pointer; border: 1px solid #e5e9f2; transition: border-color .2s, background-color .2s, box-shadow .2s; }
    .course-card:hover { border-color: #aab7f5; }
    .course-card.is-selected { border-color: var(--inscription-primary); background: var(--inscription-soft); box-shadow: 0 0 0 2px rgba(67, 97, 238, .08); }
    .course-card .course-option { flex: 0 0 auto; width: 1.1rem; height: 1.1rem; }
    .course-card .flex-grow-1 { min-width: 0; }
    .course-card .d-flex.justify-content-between { align-items: flex-start; gap: .6rem; }
    .course-card strong { min-width: 0; overflow-wrap: anywhere; line-height: 1.3; }
    .course-card .text-nowrap { flex: 0 0 auto; }
    .tour-card { border: 1px solid #e5e9f2; border-radius: 12px; padding: 1rem; }
    .tour-card.is-selected { border-color: var(--inscription-primary); background: #fafbff; }
    .payment-choice { position: relative; display: flex; align-items: center; gap: .65rem; min-height: 58px; height: 100%; padding: .55rem 2.2rem .55rem .65rem; border: 2px solid #e5e9f2; border-radius: 10px; cursor: pointer; background: #fff; transition: .2s; text-align: left; }
    .payment-choice:hover { border-color: #aab7f5; transform: translateY(-1px); }
    .payment-choice.is-selected { border-color: var(--inscription-primary); background: var(--inscription-soft); box-shadow: 0 0 0 3px rgba(67,97,238,.08); }
    .payment-choice input { position: absolute; top: .7rem; right: .75rem; }
    .payment-choice-icon { flex: 0 0 auto; width: 30px; height: 30px; display: grid; place-items: center; border-radius: 8px; background: #eef2ff; color: #4361ee; }
    .payment-choice-icon svg { width: 19px; height: 19px; }
    .payment-choice .payment-choice-icon { margin-bottom: 0 !important; }
    .payment-choice strong { font-size: .92rem; line-height: 1.2; }
    .payment-choice .badge { flex: 0 0 auto; margin-top: 0 !important; margin-left: auto; padding: .25rem .45rem; }
    .bank-payment-panel, .voucher-upload-panel { height: 100%; border: 1px solid #e5e9f2; border-radius: 12px; background: #fff; padding: .9rem; }
    .bank-payment-panel .bank-row { display: flex; justify-content: space-between; gap: 1rem; padding: .48rem 0; border-bottom: 1px solid #eef0f4; }
    .bank-payment-panel .bank-row:last-child { border-bottom: 0; }
    .voucher-upload-panel { background: #fafbff; border-style: dashed; border-color: #b8c2f3; }
    .payment-panel-icon { display: inline-grid; place-items: center; flex: 0 0 auto; width: 30px; height: 30px; border-radius: 8px; background: #eef2ff; color: #4361ee; }
    .payment-panel-icon svg { width: 17px; height: 17px; }
    .voucher-upload-panel .filepond--root { margin: .65rem 0 0; }
    .voucher-upload-panel .filepond--drop-label { min-height: 82px; }
    .form-panel { border: 1px solid #e5e9f2; border-radius: 12px; padding: .85rem; background: #fff; }
    .inscription-shell .card { margin-bottom: 0; }
    .inscription-shell hr { margin: .25rem 0; }
    .inscription-shell .table > :not(caption) > * > * { padding: .65rem .75rem; vertical-align: middle; }
    .inscription-shell .inscription-intro { padding: .8rem 1rem !important; margin-bottom: .25rem !important; }
    .form-error-summary { border-left: 4px solid #e7515a; }
    .field-valid { border-color: #00ab55 !important; background-color: #f4fff9 !important; }
    .submit-help { color: #667085; font-size: .82rem; }
    @media (max-width: 767.98px) {
        .inscription-shell { font-size: .93rem; }
        .inscription-shell .layout-top-spacing { margin-top: .75rem !important; }
        .inscription-shell .widget-header h4 { padding: .75rem 0 .4rem; font-size: 1.1rem; }
        .inscription-shell .widget-content-area { padding: .65rem .7rem 1rem; }
        .inscription-shell form.row { --bs-gutter-x: .6rem; --bs-gutter-y: .6rem; }
        .inscription-step { gap: .5rem; font-size: .95rem; }
        .inscription-step-number { width: 27px; height: 27px; }
        .form-panel, .tour-card { padding: .75rem !important; }
        .category-row td { padding-top: .7rem !important; padding-bottom: .7rem !important; }
        .inscription-shell .table th:last-child, .inscription-shell .table td:last-child { width: 88px !important; }
        .payment-choice { min-height: 54px; padding: .5rem 2rem .5rem .6rem; gap: .5rem; }
        .payment-choice .badge { font-size: .68rem; }
        .course-card .d-flex.justify-content-between { display: grid !important; grid-template-columns: minmax(0, 1fr) auto; }
        .course-card .text-nowrap { font-size: .92rem; }
        .course-card small { overflow-wrap: anywhere; }
        #dv_tranfer .card-body { padding: .8rem !important; }
        #dv_tranfer .list-group-item { padding: .45rem 0 !important; gap: .75rem; }
        #dv_tranfer .list-group-item span:last-child { word-break: break-word; }
        .bank-payment-panel, .voucher-upload-panel { padding: .75rem; }
        .bank-payment-panel .bank-row { display: block; padding: .4rem 0; }
        .bank-payment-panel .bank-row span { display: block; text-align: left !important; }
        .bank-payment-panel .bank-row span:last-child { margin-top: .1rem; overflow-wrap: anywhere; }
        .voucher-upload-panel .filepond--drop-label { min-height: 72px; }
        #paymentotal { font-size: 1.35rem; }
        .inscription-shell .btn-lg { padding: .65rem 1rem; font-size: 1rem; }
    }
</style>

<div class="layout-px-spacing inscription-shell">

    <div class="middle-content container-xxl p-0">

        <div class="row layout-spacing">
            <div class="col-lg-12 layout-top-spacing mt-4">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('error') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 mb-2 col-12">
                                <h4>
                                    {{ !empty($manualRegistration) ? 'Registrar inscripción manual' : 'Completa tu inscripción' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area pt-0">
                        <div class="inscription-intro p-3 mb-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center">
                                <div>
                                    <div class="fw-bold text-dark">{{ !empty($manualRegistration) ? 'Registro administrativo de participante' : 'Tu inscripción está a pocos pasos' }}</div>
                                    <small class="text-muted">Los campos marcados con <span class="text-danger">*</span> son obligatorios. Revisa los datos antes de continuar.</small>
                                </div>
                                <span class="badge badge-light-primary px-3 py-2">Formulario seguro</span>
                            </div>
                        </div>
                        <form class="row g-3" action="{{ !empty($manualRegistration) ? route('inscriptions.storemanualregistrationparticipant') : route('inscriptions.storemyinscription') }}" method="POST" id="formInscription" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="col-12 @if(!$errors->any()) d-none @endif" id="formErrorSummary" tabindex="-1" aria-live="assertive">
                                <div class="alert alert-danger form-error-summary mb-0">
                                    <strong>Revisa la información ingresada</strong>
                                    <div id="formErrorMessage" class="mt-1">@if($errors->any()) {{ $errors->first() }} @endif</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="inscription-step"><span class="inscription-step-number">1</span> Datos personales y de contacto</div>
                            </div>
                            <div class="col-md-4">
                                <label for="name" class="form-label text-muted mb-0">{{__("Nombre completo")}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control convert_mayus" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                                {!!$errors->first("name", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-4">
                                <label for="lastname" class="form-label text-muted mb-0">{{__("Apellido paterno")}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control convert_mayus" name="lastname" id="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                                {!!$errors->first("lastname", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-4">
                                <label for="second_lastname" class="form-label text-muted mb-0">{{__("Apellido materno")}}</label>
                                <input type="text" class="form-control convert_mayus" name="second_lastname" id="second_lastname" value="{{ old('second_lastname', $user->second_lastname) }}">
                                {!!$errors->first("second_lastname", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-4">
                                <label for="inputDocumentType" class="form-label text-muted mb-0">{{__("Tipo de documento")}} <span class="text-danger">*</span></label>
                                <select name="document_type" class="form-select" id="inputDocumentType" required>
                                    <option value="">Seleccione...</option>
                                    <option value="DNI" @if(old('document_type', $user->document_type) === 'DNI') selected @endif>DNI</option>
                                    <option value="Carnet de extranjería" @if(old('document_type', $user->document_type) === 'Carnet de extranjería') selected @endif>Carnet de extranjería</option>
                                    <option value="Pasaporte" @if(old('document_type', $user->document_type) === 'Pasaporte') selected @endif>Pasaporte</option>
                                </select>
                                {!!$errors->first("document_type", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-4">
                                <label for="inputDocumentNumber" class="form-label text-muted mb-0">{{__("Número de documento")}} <span class="text-danger">*</span></label>
                                <input type="text" name="document_number" class="form-control" id="inputDocumentNumber" value="{{ old('document_number', $user->document_number) }}" required>
                                {!!$errors->first("document_number", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-4">
                                <label for="inputCountry" class="form-label text-muted mb-0">{{__("País")}} <span class="text-danger">*</span></label>
                                <select name="country" class="form-select" id="inputCountry" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->name}}" @if(old('country', $user->country) === $country->name) selected @endif>{{$country->name}}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first("country", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-4">
                                <label for="inputState" class="form-label text-muted mb-0">{{__("Estado/Provincia")}} <span class="text-danger">*</span></label>
                                <input type="text" name="state" class="form-control" id="inputState" value="{{old('state')}}" required>
                                {!!$errors->first("state", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-4">
                                <label for="inputCity" class="form-label text-muted mb-0">{{__("Distrito/Ciudad")}} <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control" id="inputCity" value="{{old('city')}}" required>
                                {!!$errors->first("city", "<span class='text-danger'>:message</span>")!!}
                            </div>
                            <div class="col-md-8">
                                <label for="inputAddress" class="form-label text-muted mb-0">{{__("Dirección")}} <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control" id="inputAddress" value="{{old('address')}}" required>
                                {!!$errors->first("address", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-4">
                                <label for="inputPostalCode" class="form-label text-muted mb-0">{{__("Código Postal")}} <span class="text-danger">*</span></label>
                                <input type="number" name="postal_code" class="form-control" id="inputPostalCode" value="{{old('postal_code')}}" required>
                                {!!$errors->first("postal_code", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-4">
                                <label for="inputPhoneNumber" class="form-label text-muted mb-0">{{__("Teléfono")}} <span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <div class="w-25">
                                        <input type="text" inputmode="tel" name="phone_code" class="form-control rounded-0 rounded-start" id="inputPhoneCode" placeholder="+00" value="{{old('phone_code')}}" maxlength="5" pattern="\+?[0-9]{1,4}" required>
                                        <small>{{ __('Cod. País') }}</small>
                                    </div>
                                    <div class="w-25">
                                        <input type="text" inputmode="numeric" name="phone_code_city" class="form-control rounded-0" id="inputPhoneCodeCity" placeholder="01" value="{{old('phone_code_city')}}" maxlength="5" pattern="[0-9]{1,5}" required>
                                        <small>{{ __('Ciudad') }}</small>
                                    </div>
                                    <div class="w-50">
                                        <input type="text" inputmode="tel" name="phone_number" class="form-control rounded-0 rounded-end" id="inputPhoneNumber" placeholder="8765432" value="{{old('phone_number')}}" maxlength="20" pattern="\+?[0-9 ()-]{7,20}" required>
                                        <small>{{ __('Número') }}</small>
                                    </div>
                                </div>
                                {!!$errors->first("phone_code", "<span class='text-danger'>:message</span>")!!}
                                {!!$errors->first("phone_code_city", "<span class='text-danger'>:message</span>")!!}
                                {!!$errors->first("phone_number", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-4">
                                <label for="inputWhatsappNumber" class="form-label text-muted mb-0">{{__("WhatsApp")}} <span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <div class="w-25">
                                        <input type="text" inputmode="tel" name="whatsapp_code" class="form-control rounded-0 rounded-start" id="inputWhatsappCode" placeholder="+00" value="{{ old('whatsapp_code', $user->whatsapp_code) }}" maxlength="5" pattern="\+?[0-9]{1,4}" required>
                                        <small>{{ __('Cod. País') }}</small>
                                    </div>
                                    <div class="w-75">
                                        <input type="text" inputmode="tel" name="whatsapp_number" class="form-control rounded-0 rounded-end" id="inputWhatsappNumber" placeholder="8765432" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" maxlength="20" pattern="\+?[0-9 ()-]{7,20}" required>
                                        <small>{{ __('Número') }}</small>
                                    </div>
                                </div>
                                {!!$errors->first("whatsapp_code", "<span class='text-danger'>:message</span>")!!}
                                {!!$errors->first("whatsapp_number", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-12">
                                <label for="inputWorkplace" class="form-label text-muted mb-0">{{__("Centro de trabajo")}} <span class="text-danger">*</span></label>
                                <input type="text" name="workplace" class="form-control" id="inputWorkplace" value="{{old('workplace')}}" required>
                                {!!$errors->first("workplace", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            <div class="col-md-6">
                                <label for="inputEmail" class="form-label text-muted mb-0">{{__("Email")}} <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" id="inputEmail" value="{{ old('email', $user->email) }}" @if(empty($manualRegistration)) readonly @endif required>
                                {!!$errors->first("email", "<span class='text-danger'>:message</span>")!!}
                            </div>

                            @if(!empty($manualRegistration))
                                <div class="col-md-6">
                                    <label for="password" class="form-label text-muted mb-0">{{ __('Contraseña') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" id="password" minlength="8" autocomplete="new-password" required>
                                    {!!$errors->first("password", "<span class='text-danger'>:message</span>")!!}
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label text-muted mb-0">{{ __('Confirmar contraseña') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" minlength="8" autocomplete="new-password" required>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <label for="inputSolapinName" class="form-label text-muted mb-0">{{__("Solapín/Gafete")}} <span class="text-danger">*</span> <small class="fw-normal">({{ __("Un nombre y un apellido") }})</small></label>
                                <div class="d-flex gap-2">
                                    <div class="w-50">
                                        <input type="text" class="form-control convert_mayus" name="solapin_name" id="inputSolapinName" value="{{ old('solapin_name', $user->solapin_name) }}" placeholder="Nombre" required>
                                        {!!$errors->first("solapin_name", "<span class='text-danger'>:message</span>")!!}
                                    </div>
                                    <div class="w-50">
                                        <input type="text" class="form-control convert_mayus" name="solapin_lastname" id="inputSolapinLastname" value="{{ old('solapin_lastname', $user->solapin_lastname) }}" placeholder="Apellido" required>
                                        {!!$errors->first("solapin_lastname", "<span class='text-danger'>:message</span>")!!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr class="my-0">
                            </div>

                            <div class="col-md-12">
                                <div class="inscription-step"><span class="inscription-step-number">2</span> {{__("Selecciona tu categoría")}}</div>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col"><b>{{__("Categoría")}}</b></th>
                                                <th scope="col" width="105px"><b>{{__("Precio")}}</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($category_inscriptions as $category)
                                                @php
                                                    if($category->name == 'Residente'){
                                                        $infomark = ' <span class="text-danger">*</span>';
                                                    }else{
                                                        $infomark = '';
                                                    }
                                                @endphp

                                                @if ($category->type == 'radio' && $category->status == 'active')
                                                    <tr class="category-row">
                                                        <td>
                                                            <div class="form-check form-check-primary me-1">
                                                                <input type="{{ $category->type }}" id="category_{{ $category->id }}" name="category_inscription_id" value="{{ $category->id }}" class="form-check-input cursor-pointer" data-catprice="{{ $category->price }}" data-original-price="{{ $category->price }}" data-requires-document="{{ $category->requires_document ? '1' : '0' }}" data-requires-voucher="{{ $category->requires_voucher ? '1' : '0' }}" data-uses-special-code="{{ $category->uses_special_code ? '1' : '0' }}" data-shows-payment="{{ $category->shows_payment ? '1' : '0' }}" data-waives-accompanist-fee="{{ $category->waives_accompanist_fee ? '1' : '0' }}" @if((string) old('category_inscription_id') === (string) $category->id) checked @endif>
                                                                <label class="form-check-label mb-0 ms-1 cursor-pointer" for="category_{{ $category->id }}">{{ $category->name }}{!! $infomark !!}
                                                                <small class="text-muted">{!! $category->description !!}</small>
                                                                </label>
                                                            </div>

                                                            @if ($category->uses_special_code)
                                                            <div id="dv_specialcode" class="d-none">
                                                                <div class="d-sm-inline-block">
                                                                    <div class="input-group mt-1 mb-0">
                                                                        <input type="text" name="specialcode" id="specialcode" class="form-control convert_mayus" value="{{ old('specialcode') }}" placeholder="Ingresar código">
                                                                        <button class="btn btn-secondary d-none" type="button" id="clear_specialcode" style="border-radius: 0px 6px 6px 0px;">Limpiar</button>
                                                                        <button class="btn btn-primary px-2 px-sm-3" type="button" id="validate_specialcode">Validar</button>
                                                                    </div>
                                                                </div>
                                                                <div class="d-inline-block" id="sms_valid_vc">
                                                                    <!-- Mensaje -->
                                                                </div>
                                                                <input type="hidden" name="specialcode_verify" id="specialcode_verify" value="">
                                                                {!!$errors->first("specialcode", "<span class='text-danger d-block'>:message</span>")!!}
                                                            </div>
                                                            @endif

                                                        </td>
                                                        <td>
                                                            <b>US$ <span id="dc_price_{{ $category->id }}" class="category-price">{{ $category->price === '0.00' ? '00' : rtrim(rtrim($category->price, '0'), '.') }}</span></b>
                                                        </td>
                                                    </tr>

                                                @endif

                                                @if ($category->type == 'checkbox' && $category->name == 'Acompañante' && $category->status == 'active')
                                                    <tr class="category-row">
                                                        <td>
                                                            <div class="form-check form-check-primary">
                                                                <input class="form-check-input cursor-pointer" type="checkbox" name="accompanist" value="si" id="customcheck_{{ $category->id }}" data-catprice="{{ $category->price }}" @if(old('accompanist') === 'si') checked @endif>
                                                                <label class="form-check-label mb-0 ms-1 cursor-pointer" for="customcheck_{{ $category->id }}">{{ $category->name }}</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <b>US$ {{ $category->price === '0.00' ? '00' : rtrim(rtrim($category->price, '0'), '.') }}</b>
                                                        </td>
                                                    </tr>
                                                @endif

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {!!$errors->first("category_inscription_id", "<span class='text-danger d-block mt-1'>:message</span>")!!}

                                <div id="dv_document_file" class="d-none">
                                    <small class="text-danger"><b>{{__("Nota:")}}</b> {{__("* Debe adjuntar documento probatorio de categoría (Título, Constancia, Carnet profesional) (.pdf/.jpg)")}}</small>

                                    <label for="document_file" class="form-label mt-2">
                                        <span class="fw-bold">{{ __('Adjuntar documento probatorio de categoría') }}:</span> <span class="text-info">{{ __('(Título, Constancia, Carnet profesional) (.pdf/.jpg)') }}</span></label>
                                    <input type="file" name="document_file" id="document_file" class="file-control" accept="application/pdf,image/jpeg,image/png">
                                    {!!$errors->first("document_file", "<span class='text-danger d-block'>:message</span>")!!}
                                </div>

                                <div id="dv_accompanist" class="d-none mt-3">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light border-0 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ __('Datos del acompañante') }}</div>
                                                    <small class="text-muted">{{ __('Completa todos los campos para registrar al acompañante.') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-5">
                                                    <label for="accompanist_name" class="form-label text-muted mb-1">{{__("Nombre completo")}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control convert_mayus" name="accompanist_name" id="accompanist_name" value="{{ old('accompanist_name') }}">
                                                    {!!$errors->first("accompanist_name", "<span class='text-danger'>:message</span>")!!}
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="accompanist_typedocument" class="form-label text-muted mb-1">{{__("Tipo documento")}} <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="accompanist_typedocument" id="accompanist_typedocument">
                                                        <option value="">Seleccione...</option>
                                                        <option value="DNI" @if(old('accompanist_typedocument') === 'DNI') selected @endif>DNI</option>
                                                        <option value="Carnet de extranjería" @if(old('accompanist_typedocument') === 'Carnet de extranjería') selected @endif>Carnet de extranjería</option>
                                                        <option value="Pasaporte" @if(old('accompanist_typedocument') === 'Pasaporte') selected @endif>Pasaporte</option>
                                                    </select>
                                                    {!!$errors->first("accompanist_typedocument", "<span class='text-danger'>:message</span>")!!}
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="accompanist_numdocument" class="form-label text-muted mb-0">{{__("N° documento")}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="accompanist_numdocument" id="accompanist_numdocument" value="{{ old('accompanist_numdocument') }}">
                                                    {!!$errors->first("accompanist_numdocument", "<span class='text-danger'>:message</span>")!!}
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="accompanist_phone" class="form-label text-muted mb-0">{{__("Teléfono")}} <span class="text-danger">*</span></label>
                                                    <input type="text" inputmode="tel" class="form-control" name="accompanist_phone" id="accompanist_phone" value="{{ old('accompanist_phone') }}" placeholder="+51 987654321" maxlength="22" pattern="\+?[0-9 ()-]{7,22}">
                                                    {!!$errors->first("accompanist_phone", "<span class='text-danger'>:message</span>")!!}
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="accompanist_solapin" class="form-label text-muted mb-0">{{__("Solapín/Gafete")}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control convert_mayus" name="accompanist_solapin" id="accompanist_solapin" value="{{ old('accompanist_solapin') }}">
                                                    {!!$errors->first("accompanist_solapin", "<span class='text-danger'>:message</span>")!!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if($courses->isNotEmpty())
                                <div class="col-md-12">
                                    <div class="form-panel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div><div class="fw-bold text-dark">{{ __('Cursos disponibles') }}</div><small class="text-muted">Puedes seleccionar uno o varios cursos para agregarlos a tu inscripción.</small></div>
                                            <span class="badge badge-light-primary">Opcional</span>
                                        </div>
                                        <div class="row g-3">
                                            @foreach($courses as $course)
                                                @php $courseFull = $course->capacity && $course->inscriptions_count >= $course->capacity; @endphp
                                                <div class="col-md-6">
                                                    <label class="card h-100 p-3 mb-0 course-card @if(in_array($course->id, old('course_ids', []))) is-selected @endif @if($courseFull) opacity-50 @endif" for="course_{{ $course->id }}">
                                                        <div class="d-flex gap-2">
                                                            <input class="form-check-input course-option mt-1" type="checkbox" name="course_ids[]" id="course_{{ $course->id }}" value="{{ $course->id }}" data-course-price="{{ $course->price }}" @if(in_array($course->id, old('course_ids', []))) checked @endif @if($courseFull) disabled @endif>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between gap-2"><strong>{{ $course->name }}</strong><span class="text-primary fw-bold text-nowrap">US$ {{ number_format($course->price, 2) }}</span></div>
                                                                @if($course->description)<small class="text-muted d-block mt-1">{{ $course->description }}</small>@endif
                                                                <small class="d-block mt-2"><b>{{ $course->course_date ? $course->course_date->format('d/m/Y') : 'Fecha por definir' }}</b> @if($course->start_time) · {{ substr($course->start_time, 0, 5) }} @endif @if($course->location) · {{ $course->location }} @endif</small>
                                                                @if($courseFull)<small class="text-danger fw-bold">Cupos agotados</small>@elseif($course->capacity)<small class="text-muted">{{ $course->capacity - $course->inscriptions_count }} cupos disponibles</small>@endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="text-end mt-3 fw-bold">Subtotal cursos: US$ <span id="text_courses_total">0.00</span></div>
                                    </div>
                                </div>
                            @endif

                            @if($tours->isNotEmpty())
                                <div class="col-md-12"><div class="form-panel">
                                    <div class="fw-bold text-dark">{{ __('Tours disponibles') }}</div><small class="text-muted d-block mb-3">Selecciona uno o varios tours. Puedes agregar un acompañante en cada tour.</small>
                                    <div class="row g-3">
                                    @foreach($tours as $tour)
                                        @php $tourFull=$tour->capacity && $tour->sold_seats >= $tour->capacity; $tourChecked=in_array($tour->id,old('tour_ids',[])); $tourCompanion=old("tour_has_accompanist.$tour->id")=='1'; @endphp
                                        <div class="col-12"><div class="tour-card @if($tourChecked) is-selected @endif @if($tourFull) opacity-50 @endif" data-tour-card>
                                            <div class="d-flex gap-2"><input type="checkbox" class="form-check-input tour-option mt-1" name="tour_ids[]" id="tour_{{$tour->id}}" value="{{$tour->id}}" data-tour-price="{{$tour->price}}" @if($tourChecked) checked @endif @if($tourFull) disabled @endif><label for="tour_{{$tour->id}}" class="flex-grow-1 cursor-pointer"><div class="d-flex justify-content-between"><b>{{$tour->name}}</b><b class="text-primary">US$ {{number_format($tour->price,2)}}</b></div><small>{{$tour->tour_date?$tour->tour_date->format('d/m/Y'):'Fecha por definir'}} @if($tour->meeting_point) · {{$tour->meeting_point}} @endif</small></label></div>
                                            <div class="tour-companion-control mt-3 @if(!$tourChecked) d-none @endif">
                                                <div class="form-check"><input type="hidden" name="tour_has_accompanist[{{$tour->id}}]" value="0"><input type="checkbox" class="form-check-input tour-companion-option" name="tour_has_accompanist[{{$tour->id}}]" id="tour_companion_{{$tour->id}}" value="1" data-companion-price="{{$tour->accompanist_price}}" @if($tourCompanion) checked @endif><label class="form-check-label" for="tour_companion_{{$tour->id}}">Agregar acompañante al tour (+ US$ {{number_format($tour->accompanist_price,2)}})</label></div>
                                                <div class="tour-companion-fields row g-2 mt-1 @if(!$tourCompanion) d-none @endif">
                                                    <div class="col-md-4"><label class="form-label mb-0">Nombre completo *</label><input class="form-control" name="tour_companion[{{$tour->id}}][name]" value="{{old("tour_companion.$tour->id.name")}}"></div>
                                                    <div class="col-md-2"><label class="form-label mb-0">Tipo documento *</label><select class="form-select" name="tour_companion[{{$tour->id}}][document_type]"><option value="">Seleccione</option>@foreach(['DNI','Carnet de extranjería','Pasaporte'] as $type)<option value="{{$type}}" @if(old("tour_companion.$tour->id.document_type")===$type) selected @endif>{{$type}}</option>@endforeach</select></div>
                                                    <div class="col-md-3"><label class="form-label mb-0">N° documento *</label><input class="form-control" name="tour_companion[{{$tour->id}}][document_number]" value="{{old("tour_companion.$tour->id.document_number")}}"></div>
                                                    <div class="col-md-3"><label class="form-label mb-0">Teléfono *</label><input class="form-control" inputmode="tel" name="tour_companion[{{$tour->id}}][phone]" value="{{old("tour_companion.$tour->id.phone")}}"></div>
                                                </div>
                                            </div>
                                        </div></div>
                                    @endforeach
                                    </div><div class="text-end fw-bold mt-3">Subtotal tours: US$ <span id="text_tours_total">0.00</span></div>
                                </div></div>
                            @endif

                            <div class="col-md-12" id="dv_invoice">
                                <div class="card px-3 py-3">
                                    <label for="" class="form-label fw-bold">
                                        {{ __('¿Necesita Factura?') }}

                                    </label>
                                    <div class="">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input class="form-check-input cursor-pointer" type="radio" name="invoice" id="invoice_no" value="no" @if(old('invoice', 'no') === 'no') checked @endif>
                                            <label class="form-check-label mb-0 cursor-pointer" for="invoice_no">
                                                No
                                            </label>
                                        </div>
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input class="form-check-input cursor-pointer" type="radio" name="invoice" id="invoice_yes" value="si" @if(old('invoice') === 'si') checked @endif>
                                            <label class="form-check-label mb-0 cursor-pointer" for="invoice_yes">
                                                Si
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row mt-2 d-none" id="dv_invoice_info">
                                        <div class="col-md-4">
                                            <label for="invoice_ruc" class="form-label fw-semibold mb-1">{{ __('RUC') }} <span class="text-danger">*</span></label>
                                            <input type="text" inputmode="numeric" name="invoice_ruc" id="invoice_ruc" class="form-control" value="{{ old('invoice_ruc') }}" placeholder="Ingrese 11 dígitos" minlength="11" maxlength="11" pattern="[0-9]{11}" autocomplete="off">
                                            <small class="text-muted">Solo números, 11 dígitos.</small>
                                            {!!$errors->first("invoice_ruc", "<span class='text-danger d-block'>:message</span>")!!}
                                        </div>
                                        <div class="col-md-4">
                                            <label for="invoice_social_reason" class="form-label fw-semibold mb-1">{{ __('Razón social') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="invoice_social_reason" id="invoice_social_reason" class="form-control" value="{{ old('invoice_social_reason') }}" placeholder="Razón social">
                                            {!!$errors->first("invoice_social_reason", "<span class='text-danger d-block'>:message</span>")!!}
                                        </div>
                                        <div class="col-md-4">
                                            <label for="invoice_address" class="form-label fw-semibold mb-1">{{ __('Dirección fiscal') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="invoice_address" id="invoice_address" class="form-control" value="{{ old('invoice_address') }}" placeholder="Dirección">
                                            {!!$errors->first("invoice_address", "<span class='text-danger d-block'>:message</span>")!!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card border-0 bg-dark text-white px-3 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold fs-5">{{ __('TOTAL A PAGAR') }}</span>
                                        <span class="fw-bold fs-4">US$ <span id="paymentotal">0.00</span></span>
                                    </div>
                                    <small class="text-white-50" id="totalConceptsText">Selecciona los conceptos de la inscripción.</small>
                                </div>
                            </div>

                            <div class="col-md-12 d-none" id="dv_payment">
                                <div class="card px-3 py-3">
                                    <div class="inscription-step mb-3">
                                        <span class="inscription-step-number">3</span>
                                        <span>{{ __('FORMA DE PAGO') }}</span>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6"><label class="payment-choice" for="payment_method_transfer"><input class="form-check-input" type="radio" name="payment_method" value="Transferencia/Depósito" id="payment_method_transfer" @if(old('payment_method','Transferencia/Depósito')==='Transferencia/Depósito') checked @endif><span class="payment-choice-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18"></path><path d="M5 10v8"></path><path d="M9 10v8"></path><path d="M15 10v8"></path><path d="M19 10v8"></path><path d="M3 18h18"></path><path d="m12 3 9 4H3l9-4Z"></path></svg></span><strong>Transferencia o depósito</strong><span class="badge badge-light-success">Disponible</span></label></div>
                                        <div class="col-md-6"><label class="payment-choice" for="payment_method_card"><input class="form-check-input" type="radio" name="payment_method" value="Tarjeta" id="payment_method_card" @if(old('payment_method')==='Tarjeta') checked @endif><span class="payment-choice-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"></rect><path d="M2 10h20"></path><path d="M6 15h2"></path></svg></span><strong>Tarjeta</strong><span class="badge badge-light-info">Próximamente</span></label></div>
                                    </div>

                                    <div id="dv_tranfer" class="mt-3">
                                        <div class="row g-3 align-items-stretch">
                                            <div class="col-lg-7">
                                                <div class="bank-payment-panel">
                                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1"><div class="d-flex align-items-center gap-2"><span class="payment-panel-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18"></path><path d="M5 10v8"></path><path d="M9 10v8"></path><path d="M15 10v8"></path><path d="M19 10v8"></path><path d="M3 18h18"></path><path d="m12 3 9 4H3l9-4Z"></path></svg></span><strong>Realiza la transferencia</strong></div><span class="badge badge-light-primary">Cuenta en dólares</span></div>
                                                    <div class="bank-row"><span class="text-muted">Beneficiario</span><span class="fw-bold text-end">ASOCIACION CONGRESO LIMA-PERU</span></div>
                                                    <div class="bank-row"><span class="text-muted">Banco</span><span class="fw-bold text-end">BCP · Banco de Crédito del Perú</span></div>
                                                    <div class="bank-row"><span class="text-muted">Cuenta</span><span class="fw-bold text-primary text-end">194-7417292-1-50</span></div>
                                                    <div class="bank-row"><span class="text-muted">CCI</span><span class="fw-semibold text-end">002 194 007417292150 99</span></div>
                                                    <div class="mt-2 p-2 rounded bg-light"><div class="d-flex justify-content-between"><small class="text-muted">SWIFT internacional</small><small class="fw-bold">BCPLPEPL</small></div><small class="text-muted d-block mt-1">Dirección: Calle Centenario 156, La Molina, Lima.</small></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="voucher-upload-panel" id="dv_voucher_file">
                                                    <div class="d-flex gap-2 align-items-start"><span class="payment-panel-icon" aria-hidden="true"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path><path d="M14 2v6h6"></path><path d="M12 18v-6"></path><path d="m9 15 3-3 3 3"></path></svg></span><div><strong class="d-block">Adjunta tu comprobante</strong><small class="text-muted">PDF, JPG o PNG · máximo 10 MB.</small></div></div>
                                                    <input type="file" name="voucher_file" id="voucher_file" class="file-control" accept="application/pdf,image/jpeg,image/png">
                                                    <small id="cprequired" class="text-danger d-block mt-1">El comprobante es requerido para validar el pago.</small>
                                                    {!!$errors->first("voucher_file", "<span class='text-danger d-block'>:message</span>")!!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="dv_card" class="pt-4 pb-4">
                                        <p class="text-center">
                                            <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
                                                Próximamente, podrás pagar tu inscripción con tarjeta de crédito/débito.
                                            </div>
                                        </p>
                                    </div>

                                </div>
                            </div>
                            
                            <div class="col-md-12 d-none" id="sms_extranjero">
                                <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                                    <strong>{{__("Nota:")}}</strong> Su información será validada y, una vez confirmada, recibirá un correo electrónico con la confirmación de su inscripción.
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                @if(!empty($manualRegistration))
                                    <a href="{{ route('inscriptions.index') }}" class="btn btn-secondary btn-lg">{{ __('Cancelar') }}</a>
                                @endif
                                <button type="submit" class="btn btn-primary btn-lg" id="btnSubInscription">{{ !empty($manualRegistration) ? __('Registrar inscripción') : __('Inscribirme Ahora') }}</button>
                                <div class="submit-help mt-2" id="submitHelp">Al continuar confirmas que la información ingresada es correcta.</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
