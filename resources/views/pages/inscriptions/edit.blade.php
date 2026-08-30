@extends('layouts.app')


@section('content')

<style>
    .inscription-shell { --inscription-primary: #4361ee; --inscription-soft: #eef2ff; }
    .inscription-intro { background: linear-gradient(135deg, #eef2ff 0%, #f8faff 100%); border: 1px solid #dce4ff; border-radius: 14px; }
    .inscription-step { display: flex; align-items: center; gap: .65rem; color: #344054; font-weight: 700; }
    .inscription-step-number { display: inline-grid; place-items: center; width: 30px; height: 30px; border-radius: 50%; background: var(--inscription-primary); color: #fff; font-size: .85rem; }
    .inscription-shell form .row, .inscription-shell form.row { --bs-gutter-x: .85rem; --bs-gutter-y: .7rem; }
    .inscription-shell .widget-content-area { padding: 1rem 1.25rem 1.25rem; }
    .inscription-shell .form-label { font-size: .86rem; }
    .inscription-shell .form-control, .inscription-shell .form-select { min-height: 40px; padding-top: .45rem; padding-bottom: .45rem; transition: border-color .2s, box-shadow .2s, background-color .2s; }
    .inscription-shell .form-control:focus, .inscription-shell .form-select:focus { border-color: #7186ee; box-shadow: 0 0 0 .2rem rgba(67, 97, 238, .12); }
    .category-row { cursor: pointer; transition: background-color .2s, box-shadow .2s; }
    .category-row:hover { background: #f8faff; }
    .category-row.is-selected { background: var(--inscription-soft); box-shadow: inset 4px 0 0 var(--inscription-primary); }
    .category-row.is-selected label { color: #263b98; font-weight: 700; }
    .course-card { cursor: pointer; border: 1px solid #e5e9f2; transition: .2s; }
    .course-card.is-selected { border-color: var(--inscription-primary); background: var(--inscription-soft); }
    .course-card .course-option { flex: 0 0 auto; width: 1.1rem; height: 1.1rem; }
    .course-card .flex-grow-1 { min-width: 0; }
    .course-card .d-flex.justify-content-between { align-items: flex-start; gap: .6rem; }
    .course-card strong { min-width: 0; overflow-wrap: anywhere; line-height: 1.3; }
    .payment-choice { position: relative; display: flex; align-items: center; min-height: 54px; height: 100%; padding: .55rem 2.2rem .55rem .7rem; border: 2px solid #e5e9f2; border-radius: 10px; cursor: pointer; text-align: left; transition: .2s; }
    .payment-choice.is-selected { border-color: var(--inscription-primary); background: var(--inscription-soft); }
    .payment-choice input { position: absolute; top: .65rem; right: .7rem; }
    .payment-choice strong { font-size: .9rem; line-height: 1.2; }
    .form-panel { border: 1px solid #e5e9f2; border-radius: 12px; padding: .85rem; background: #fff; }
    .inscription-shell .card { margin-bottom: 0; }
    .inscription-shell .table > :not(caption) > * > * { padding: .65rem .75rem; vertical-align: middle; }
    .form-error-summary { border-left: 4px solid #e7515a; }
    @media (max-width: 767.98px) {
        .inscription-shell { font-size: .93rem; }
        .inscription-shell .layout-top-spacing { margin-top: .75rem !important; }
        .inscription-shell .widget-header { padding-left: .7rem !important; padding-right: .7rem !important; }
        .inscription-shell .widget-header h4 { font-size: 1.05rem; }
        .inscription-shell .widget-content-area { padding: .65rem .7rem 1rem; }
        .inscription-shell form .row, .inscription-shell form.row { --bs-gutter-x: .6rem; --bs-gutter-y: .6rem; }
        .form-panel, .tour-card { padding: .75rem !important; }
        .inscription-step { font-size: .95rem; gap: .5rem; }
        .inscription-step-number { width: 27px; height: 27px; }
        .inscription-shell .table > :not(caption) > * > * { padding: .55rem .6rem; }
        .inscription-shell .table th:last-child, .inscription-shell .table td:last-child { width: 96px !important; }
        .payment-choice { min-height: 50px; padding: .5rem 2rem .5rem .65rem; }
        .course-card .d-flex.justify-content-between { display: grid !important; grid-template-columns: minmax(0, 1fr) auto; }
        .course-card .text-primary { font-size: .92rem; white-space: nowrap; }
        #text_total { font-size: 1.35rem; }
        #actionbtn { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
        #actionbtn .btn { margin-top: 0 !important; }
    }
</style>

<div class="layout-px-spacing inscription-shell">

    <div class="middle-content container-xxl p-0">

        <div class="row layout-spacing">
            <div class="col-lg-12 layout-top-spacing mt-4">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger form-error-summary">
                        <strong>{{ __('Revisa los datos ingresados:') }}</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('inscriptions.update', $inscription->id) }}" method="POST" enctype="multipart/form-data" id="editInscriptionForm">
                    @csrf
                    @method('PUT')
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header px-3">
                            <div class="row g-3">
                                <div class="col-md-8 py-3">
                                    <h4 class="px-0 py-0">
                                        {{__("Editando Inscripción")}} # {{ $inscription->id }}
                                    </h4>
                                </div>
                                <div class="col-md-4 py-3 text-end">
                                    @php
                                        if($inscription->payment_method == 'Tarjeta'){
                                            $textmp = 'TC';
                                        }else{
                                            $textmp = 'DT';
                                        }
                                    @endphp

                                    @if($inscription->status == 'Pagado')
                                        <span class="badge badge-light-success">{{ $inscription->status .' ('.$textmp.')' }}</span>
                                    @elseif ($inscription->status == 'Procesando')
                                        <span class="badge badge-light-info">{{ $inscription->status .' ('.$textmp.')' }}</span>
                                    @elseif ($inscription->status == 'Pendiente')
                                        <span class="badge badge-light-warning">{{ $inscription->status .' ('.$textmp.')' }}</span>
                                    @elseif ($inscription->status == 'Rechazado')
                                        <span class="badge badge-light-danger">{{ $inscription->status .' ('.$textmp.')' }}</span>
                                    @endif
                                    <span class="d-block">{{ $inscription->created_at }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area pt-0">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="inscription-intro p-3">
                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center">
                                            <div>
                                                <div class="fw-bold text-dark">Corrección administrativa de la inscripción</div>
                                                <small class="text-muted">Los cambios quedarán registrados en el historial. Revisa cuidadosamente los datos antes de actualizar.</small>
                                            </div>
                                            <span class="badge badge-light-primary px-3 py-2">Solo administradores</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="inscription-step"><span class="inscription-step-number">1</span> Datos personales y de contacto</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="name" class="form-label fw-bold mb-0">{{__("Nombres")}}:</label>
                                    <input type="text" class="form-control convert_mayus" name="name" id="name" value="{{ old('name', $inscription->user_name) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="lastname" class="form-label fw-bold mb-0">{{__("Apellido paterno")}}:</label>
                                    <input type="text" class="form-control convert_mayus" name="lastname" id="lastname" value="{{ old('lastname', $inscription->user_lastname) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="second_lastname" class="form-label fw-bold mb-0">{{__("Apellido materno")}}:</label>
                                    <input type="text" class="form-control convert_mayus" name="second_lastname" id="second_lastname" value="{{ old('second_lastname', $inscription->user_second_lastname) }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="document_type" class="form-label fw-bold mb-0">{{__("Tipo de documento")}}:</label>
                                    <select class="form-select" name="document_type" id="document_type" required>
                                        @foreach(['DNI', 'Carnet de extranjería', 'Pasaporte'] as $documentType)
                                            <option value="{{ $documentType }}" @if(strtolower(old('document_type', $inscription->user_document_type)) === strtolower($documentType)) selected @endif>{{ $documentType }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="document_number" class="form-label fw-bold mb-0">{{__("Número de documento")}}:</label>
                                    <input type="text" class="form-control" name="document_number" id="document_number" value="{{ old('document_number', $inscription->user_document_number) }}" required>
                                </div>

                                <div class="col-md-4"></div>

                                <div class="col-md-4">
                                    <label for="country" class="form-label fw-bold mb-0">{{__("País")}}:</label>
                                    <select class="form-select" name="country" id="country" required>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->name }}" @if(old('country', $inscription->user_country) === $country->name) selected @endif>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="state" class="form-label fw-bold mb-0">{{__("Estado/Provincia")}}:</label>
                                    <input type="text" class="form-control" name="state" id="state" value="{{ old('state', $inscription->user_state) }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="city" class="form-label fw-bold mb-0">{{__("Distrito/Ciudad")}}:</label>
                                    <input type="text" class="form-control" name="city" id="city" value="{{ old('city', $inscription->user_city) }}" required>
                                </div>

                                <div class="col-md-8">
                                    <label for="address" class="form-label fw-bold mb-0">{{__("Dirección")}}:</label>
                                    <input type="text" class="form-control" name="address" id="address" value="{{ old('address', $inscription->user_address) }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="postal_code" class="form-label fw-bold mb-0">{{__("Código Postal")}}:</label>
                                    <input type="text" class="form-control" name="postal_code" id="postal_code" value="{{ old('postal_code', $inscription->user_postal_code) }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold mb-0">{{__("Teléfono")}}:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="phone_code" value="{{ old('phone_code', $inscription->user_phone_code) }}" placeholder="+51" required>
                                        <input type="text" class="form-control" name="phone_code_city" value="{{ old('phone_code_city', $inscription->user_phone_code_city) }}" placeholder="01" required>
                                        <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $inscription->user_phone_number) }}" placeholder="Número" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold mb-0">{{__("WhatsApp")}}:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="whatsapp_code" value="{{ old('whatsapp_code', $inscription->user_whatsapp_code) }}" placeholder="+51" required>
                                        <input type="text" class="form-control" name="whatsapp_number" value="{{ old('whatsapp_number', $inscription->user_whatsapp_number) }}" placeholder="Número" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="email" class="form-label fw-bold mb-0">{{__("Email")}}:</label>
                                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $inscription->user_email) }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="workplace" class="form-label fw-bold mb-0">{{__("Centro de trabajo")}}:</label>
                                    <input type="text" class="form-control" name="workplace" id="workplace" value="{{ old('workplace', $inscription->user_workplace) }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold mb-0">{{__("Solapín/Gafete")}}:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control convert_mayus" name="solapin_name" value="{{ old('solapin_name', $inscription->user_solapin_name) }}" placeholder="Nombre" required>
                                        <input type="text" class="form-control convert_mayus" name="solapin_lastname" value="{{ old('solapin_lastname', $inscription->user_solapin_lastname) }}" placeholder="Apellido" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <hr class="my-0">
                                </div>

                                <div class="col-md-12">
                                    <div class="inscription-step mb-3"><span class="inscription-step-number">2</span> {{__("Selecciona tu categoría")}}</div>
                                    <div class="table-responsive mb-3">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col"><b>{{__("Categoría")}}</b></th>
                                                    <th scope="col" width="160px"><b>{{__("Precio")}}</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($category_inscriptions as $item)
                                                    <tr class="category-row @if((string) old('category_inscription_id', $inscription->category_inscription_id) === (string) $item->id) is-selected @endif">
                                                        <td>
                                                            <div class="form-check form-check-primary mb-0">
                                                                <input type="radio" id="category_{{ $item->id }}" name="category_inscription_id" value="{{ $item->id }}" class="form-check-input cursor-pointer category-option" data-catprice="{{ $item->price }}" data-uses-special-code="{{ $item->uses_special_code ? '1' : '0' }}" @if((string) old('category_inscription_id', $inscription->category_inscription_id) === (string) $item->id) checked @endif required>
                                                                <label class="form-check-label mb-0 ms-1 cursor-pointer" for="category_{{ $item->id }}">{{ $item->name }}</label>
                                                            </div>
                                                        </td>
                                                        <td class="text-end fw-semibold">US$ {{ number_format($item->price, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="table-light">
                                                    <td><label for="price_category" class="mb-0">{{ __('Precio de categoría aplicado') }}</label></td>
                                                    <td>
                                                        <div class="input-group mb-0">
                                                            <span class="input-group-text">US$</span>
                                                            <input type="number" min="0" step="0.01" name="price_category" class="form-control" id="price_category" value="{{ old('price_category', $inscription->price_category) }}" required>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="table-light">
                                                    <td><label for="price_accompanist" class="mb-0">{{ __('Precio de acompañante aplicado') }}</label></td>
                                                        <td>
                                                            <div class="input-group mb-0">
                                                                <span class="input-group-text">US$</span>
                                                                <input type="number" min="0" step="0.01" name="price_accompanist" class="form-control" id="price_accompanist" value="{{ old('price_accompanist', $inscription->price_accompanist) }}" required>
                                                            </div>
                                                        </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="dv_special_code" class="form-panel mb-3 @if($inscription->special_code != '') @else d-none @endif">
                                        <label for="special_code" class="form-label text-muted mb-0">{{ __('Código especial') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="special_code" class="form-control" id="special_code" value="{{ old('special_code', $inscription->special_code) }}" placeholder="Código especial">
                                    </div>

                                    <div id="dv_document_file">
                                        <label class="form-label mt-3">
                                        <span class="fw-bold">{{ __('Documento probatorio de categoría ') }} ({{ $inscription->category_inscription_name }}):</span></label><br>
                                        <div class="mt-1 d-flex">
                                            @if ($inscription->document_file != null || $inscription->document_file != '')
                                            <a href="{{ asset('storage/uploads/document_file').'/'.$inscription->document_file}}" class="badge badge-light-primary text-start me-2 bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Descargar" target="_blank">
                                                {{ $inscription->document_file }}
                                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m7 10 5 5 5-5"></path><path d="M12 15V3"></path></svg>
                                            </a>
                                            <div class="mt-0">
                                                <a href="#" class="px-1 py-1 text-danger" id="change_document_file">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-pen-line"><path d="m18 5-2.414-2.414A2 2 0 0 0 14.172 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2"/><path d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><path d="M8 18h1"/></svg>
                                                </a>
                                            </div>
                                            @else
                                            <span class="badge badge-light-danger mb-2 text-start me-2 bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="No hay documento" target="_blank">
                                                {{ __('No hay documento') }}
                                            </span>
                                            @endif
                                        </div>
                                        <div class="mt-1 d-block  @if($inscription->document_file != null || $inscription->document_file != '') d-none @endif" id="dv_document_file_upload">
                                            <label for="document_file" class="mb-0 d-block">Adjuntar documento probatorio de categoría</label>
                                            <input type="file" name="document_file" class="form-control form-control-sm mt-1 p-1" id="document_file" accept="application/pdf,image/jpeg,image/png">
                                        </div>
                                    </div>

                                    <div class="form-panel mt-3">
                                        <div class="form-check mb-0">
                                            <input type="hidden" name="has_accompanist" value="0">
                                            <input class="form-check-input cursor-pointer" type="checkbox" name="has_accompanist" value="1" id="accompanist" @if(old('has_accompanist', $inscription->accompanist_id ? '1' : '0') == '1') checked @endif>
                                            <label class="form-check-label fw-bold cursor-pointer" for="accompanist">{{ __('La inscripción incluye acompañante') }}</label>
                                        </div>

                                        <div id="dv_accompanist" class="mt-3 @if(old('has_accompanist', $inscription->accompanist_id ? '1' : '0') == '1') @else d-none @endif">
                                            <div class="fw-bold mb-2">{{ __('Complete los datos del acompañante') }}</div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="accompanist_name" class="form-label text-muted mb-0">{{__("Nombre completo")}} <span class="text-danger">*</span></label>
                                                    <input type="text" name="accompanist_name" class="form-control" id="accompanist_name" value="{{ old('accompanist_name', $inscription->accompanist_name) }}" placeholder="Nombre y apellidos">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="accompanist_typedocument" class="form-label text-muted mb-0">{{__("Tipo documento")}} <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="accompanist_typedocument" id="accompanist_typedocument">
                                                        <option value="">Seleccione</option>
                                                        <option value="DNI" @if(old('accompanist_typedocument', $inscription->accompanist_typedocument) == 'DNI') selected @endif>DNI</option>
                                                        <option value="Carnet de extranjería" @if(strtolower(old('accompanist_typedocument', $inscription->accompanist_typedocument)) == 'carnet de extranjería') selected @endif>Carnet de extranjería</option>
                                                        <option value="Pasaporte" @if(old('accompanist_typedocument', $inscription->accompanist_typedocument) == 'Pasaporte') selected @endif>Pasaporte</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="accompanist_numdocument" class="form-label text-muted mb-0">{{__("N° documento")}} <span class="text-danger">*</span></label>
                                                    <input type="text" name="accompanist_numdocument" class="form-control" id="accompanist_numdocument" value="{{ old('accompanist_numdocument', $inscription->accompanist_numdocument) }}" placeholder="N° documento">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="accompanist_phone" class="form-label text-muted mb-0">{{__("Teléfono")}} <span class="text-danger">*</span></label>
                                                    <input type="text" inputmode="tel" name="accompanist_phone" class="form-control" id="accompanist_phone" value="{{ old('accompanist_phone', $inscription->accompanist_phone) }}" placeholder="+51 987654321" maxlength="22" pattern="\+?[0-9 ()-]{7,22}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="accompanist_solapin" class="form-label text-muted mb-0">{{__("Solapín/Gafete")}} <span class="text-danger">*</span></label>
                                                    <input type="text" name="accompanist_solapin" class="form-control convert_mayus" id="accompanist_solapin" value="{{ old('accompanist_solapin', $inscription->accompanist_solapin) }}" placeholder="Solapín/Gafete">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                @if($courses->isNotEmpty())
                                    <div class="col-md-12">
                                        <div class="form-panel">
                                            <div class="fw-bold mb-1">{{ __('Cursos') }}</div>
                                            <small class="text-muted d-block mb-3">Selecciona los cursos incluidos en esta inscripción.</small>
                                            <div class="row g-3">
                                                @foreach($courses as $course)
                                                    @php
                                                        $courseSelected = in_array($course->id, old('course_ids', $selectedCourseIds));
                                                        $courseFull = !$courseSelected && $course->capacity && $course->inscriptions_count >= $course->capacity;
                                                    @endphp
                                                    <div class="col-md-6"><label class="card h-100 p-3 mb-0 course-card @if($courseSelected) is-selected @endif @if($courseFull) opacity-50 @endif" for="edit_course_{{ $course->id }}">
                                                        <div class="d-flex gap-2"><input type="checkbox" class="form-check-input course-option mt-1" name="course_ids[]" id="edit_course_{{ $course->id }}" value="{{ $course->id }}" data-course-price="{{ $courseSelected ? $selectedCoursePrices[$course->id] : $course->price }}" @if($courseSelected) checked @endif @if($courseFull) disabled @endif>
                                                            <div class="flex-grow-1"><div class="d-flex justify-content-between"><strong>{{ $course->name }}</strong><span class="text-primary fw-bold">US$ {{ number_format($course->price, 2) }}</span></div><small>{{ $course->course_date ? $course->course_date->format('d/m/Y') : 'Fecha por definir' }} @if($course->location) · {{ $course->location }} @endif</small> @if($courseFull)<small class="d-block text-danger">Cupos agotados</small> @endif</div>
                                                        </div>
                                                    </label></div>
                                                @endforeach
                                            </div>
                                            <div class="text-end fw-bold mt-3">Subtotal cursos: US$ <span id="text_courses_total">0.00</span></div>
                                        </div>
                                    </div>
                                @endif

                                @if($tours->isNotEmpty())
                                <div class="col-md-12"><div class="form-panel"><div class="fw-bold">Tours</div><small class="text-muted d-block mb-3">Selecciona los tours y registra el acompañante correspondiente.</small><div class="row g-3">
                                @foreach($tours as $tour)
                                    @php $selectedTour=$selectedTours->get($tour->id); $tourChecked=in_array($tour->id,old('tour_ids',$selectedTours->keys()->all())); $hasTourCompanion=old("tour_has_accompanist.$tour->id",$selectedTour&&$selectedTour->pivot->has_accompanist?'1':'0')=='1'; $full=!$tourChecked&&$tour->capacity&&$tour->sold_seats >= $tour->capacity; @endphp
                                    <div class="col-12"><div class="tour-card card p-3 @if($tourChecked) border-primary @endif @if($full) opacity-50 @endif" data-tour-card><div class="d-flex gap-2"><input type="checkbox" class="form-check-input tour-option" name="tour_ids[]" id="edit_tour_{{$tour->id}}" value="{{$tour->id}}" data-tour-price="{{$selectedTour?$selectedTour->pivot->unit_price:$tour->price}}" @if($tourChecked) checked @endif @if($full) disabled @endif><label class="flex-grow-1" for="edit_tour_{{$tour->id}}"><span class="d-flex justify-content-between"><b>{{$tour->name}}</b><b>US$ {{number_format($tour->price,2)}}</b></span></label></div>
                                    <div class="tour-companion-control mt-2 @if(!$tourChecked) d-none @endif"><input type="hidden" name="tour_has_accompanist[{{$tour->id}}]" value="0"><div class="form-check"><input type="checkbox" class="form-check-input tour-companion-option" name="tour_has_accompanist[{{$tour->id}}]" id="edit_tour_companion_{{$tour->id}}" value="1" data-companion-price="{{$selectedTour&&$selectedTour->pivot->has_accompanist?$selectedTour->pivot->accompanist_price:$tour->accompanist_price}}" @if($hasTourCompanion) checked @endif><label class="form-check-label" for="edit_tour_companion_{{$tour->id}}">Agregar acompañante (+ US$ {{number_format($tour->accompanist_price,2)}})</label></div>
                                    <div class="tour-companion-fields row g-2 mt-1 @if(!$hasTourCompanion) d-none @endif"><div class="col-md-4"><input class="form-control" placeholder="Nombre completo" name="tour_companion[{{$tour->id}}][name]" value="{{old("tour_companion.$tour->id.name",$selectedTour?$selectedTour->pivot->accompanist_name:'')}}"></div><div class="col-md-2"><select class="form-select" name="tour_companion[{{$tour->id}}][document_type]"><option value="">Tipo documento</option>@foreach(['DNI','Carnet de extranjería','Pasaporte'] as $type)<option value="{{$type}}" @if(old("tour_companion.$tour->id.document_type",$selectedTour?$selectedTour->pivot->accompanist_document_type:'')===$type) selected @endif>{{$type}}</option>@endforeach</select></div><div class="col-md-3"><input class="form-control" placeholder="N° documento" name="tour_companion[{{$tour->id}}][document_number]" value="{{old("tour_companion.$tour->id.document_number",$selectedTour?$selectedTour->pivot->accompanist_document_number:'')}}"></div><div class="col-md-3"><input class="form-control" placeholder="Teléfono" name="tour_companion[{{$tour->id}}][phone]" value="{{old("tour_companion.$tour->id.phone",$selectedTour?$selectedTour->pivot->accompanist_phone:'')}}"></div></div></div></div></div>
                                @endforeach
                                </div><div class="text-end fw-bold mt-3">Subtotal tours: US$ <span id="text_tours_total">0.00</span></div></div></div>
                                @endif

                                <div class="col-md-12" id="dv_invoice">
                                    <div class="card px-3 py-3">
                                        <label class="form-label fw-bold mb-1">{{ __('¿Necesita Factura?') }}</label>
                                        <div class="mb-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="invoice" id="invoice_no" value="no" @if(old('invoice', $inscription->invoice) !== 'si') checked @endif>
                                                <label class="form-check-label" for="invoice_no">No</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="invoice" id="invoice_yes" value="si" @if(old('invoice', $inscription->invoice) === 'si') checked @endif>
                                                <label class="form-check-label" for="invoice_yes">Sí</label>
                                            </div>
                                        </div>

                                        <div class="row mt-1 @if(old('invoice', $inscription->invoice) !== 'si') d-none @endif" id="dv_invoice_info">
                                            <div class="col-md-4">
                                                <label for="invoice_ruc" class="form-label fw-bold mb-0">RUC:</label>
                                                <input type="text" inputmode="numeric" class="form-control" name="invoice_ruc" id="invoice_ruc" minlength="11" maxlength="11" pattern="[0-9]{11}" autocomplete="off" value="{{ old('invoice_ruc', $inscription->invoice_ruc) }}">
                                                <small class="text-muted">Solo números, 11 dígitos.</small>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="invoice_social_reason" class="form-label fw-bold mb-0">{{ __('Razón social') }}</label>
                                                <input type="text" class="form-control" name="invoice_social_reason" id="invoice_social_reason" value="{{ old('invoice_social_reason', $inscription->invoice_social_reason) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="invoice_address" class="form-label fw-bold mb-0">{{ __('Dirección fiscal') }}</label>
                                                <input type="text" class="form-control" name="invoice_address" id="invoice_address" value="{{ old('invoice_address', $inscription->invoice_address) }}">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="card border-0 bg-dark text-white px-3 py-3">
                                        <input type="hidden" name="total" id="total" value="{{ $inscription->total }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold fs-5">{{ __('TOTAL A PAGAR') }}</span>
                                            <span class="fw-bold fs-4">US$ <span id="text_total">{{ number_format($inscription->total, 2) }}</span></span>
                                        </div>
                                        <small class="text-white-50" id="totalConceptsText">Selecciona los conceptos de la inscripción.</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="card px-3 py-3">
                                        <div class="inscription-step mb-3"><span class="inscription-step-number">3</span> <span>{{ __('FORMA DE PAGO') }}</span></div>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6"><label class="payment-choice" for="payment_method_transfer"><input class="form-check-input" type="radio" name="payment_method" value="Transferencia/Depósito" id="payment_method_transfer" @if(old('payment_method',$inscription->payment_method)==='Transferencia/Depósito') checked @endif required><strong class="pe-4">Transferencia o depósito</strong></label></div>
                                            <div class="col-md-6"><label class="payment-choice" for="payment_method_card"><input class="form-check-input" type="radio" name="payment_method" value="Tarjeta" id="payment_method_card" @if(old('payment_method',$inscription->payment_method)==='Tarjeta') checked @endif required><strong class="pe-4">Tarjeta</strong></label></div>
                                        </div>

                                        @if($inscription->voucher_file != null || $inscription->voucher_file != '')
                                            <div class="row mt-1">
                                                <div class="col-md-12 d-flex">
                                                    <div class="mt-1">
                                                        <a href="{{ asset('storage/uploads/voucher_file').'/'.$inscription->voucher_file}}" class="badge badge-light-primary text-start me-2 bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Descargar" target="_blank">
                                                            {{ $inscription->voucher_file }}
                                                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m7 10 5 5 5-5"></path><path d="M12 15V3"></path></svg>
                                                        </a>
                                                    </div>
                                                    <div class="mt-1">
                                                        <a href="#" class="px-1 py-1 text-danger" id="change_voucher_file">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-pen-line"><path d="m18 5-2.414-2.414A2 2 0 0 0 14.172 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2"/><path d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><path d="M8 18h1"/></svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-1 @if($inscription->voucher_file != null || $inscription->voucher_file != '') d-none @endif" id="dv_voucher_file">
                                            <label for="voucher_file" class="mb-0 d-block">Adjuntar comprobante de pago</label>
                                            <input type="file" name="voucher_file" class="form-control form-control-sm mt-1 p-1" id="voucher_file" accept="application/pdf,image/jpeg,image/png">
                                        </div>

                                        @if ($inscription->payment_method == 'Tarjeta' && $paymentcard != null)
                                        <div class="row mt-1">
                                            <div class="col-2">
                                                <label class="form-label fw-bold mb-0">{{__("# de compra")}}:</label><br>
                                                <span class="bx-text">{{ $paymentcard->purchasenumber }}</span>
                                            </div>
                                            <div class="col-2">
                                                <label class="form-label fw-bold mb-0">{{__("Tarjeta")}}:</label><br>
                                                <span class="bx-text">{{ $paymentcard->card_brand }}</span>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label fw-bold mb-0">{{__("#")}}:</label><br>
                                                <span class="bx-text">{{ $paymentcard->card_number }}</span>
                                            </div>
                                            <div class="col-2">
                                                <label class="form-label fw-bold mb-0">{{__("Monto")}}:</label><br>
                                                <span class="bx-text">{{ $paymentcard->amount.' '.$paymentcard->currency }}</span>
                                            </div>
                                            <div class="col-3">
                                                @php
                                                    $carbonTDate = \Carbon\Carbon::createFromFormat('ymdHis', $paymentcard->transaction_date);
                                                    $tansactionDate = $carbonTDate->format('Y-m-d H:i:s');
                                                @endphp
                                                <label class="form-label fw-bold mb-0">{{__("Fecha Trans.")}}:</label><br>
                                                <span class="bx-text">{{ $tansactionDate }}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <span class="bx-text">{{ $paymentcard->action_description }}</span>
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </div>


                                <div class="col-md-7">

                                    <div class="card p-2">
                                        <ul class="mb-0">
                                            @foreach ($statusnotes as $item)
                                                <li>
                                                    <b class="text-info">{!! $item->note !!}</b> ({{ $item->created_at }})<br>
                                                    <small>{{ $item->action }}</small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                </div>



                                <div class="col-md-5 text-end align-self-end">

                                    @if(\Auth::user()->hasRole('Administrador'))
                                            <div id="actionbtn">
                                                <a href="{{ route('inscriptions.show', $inscription->id) }}" class="btn btn-secondary mt-2">
                                                    {{ __('Cancelar') }}
                                                </a>

                                                <button type="submit" class="btn btn-primary mt-2" id="btnUpdateInscription">
                                                    {{ __('Actualizar') }}
                                                </button>
                                            </div>
                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>

@endsection


<script>

document.addEventListener("DOMContentLoaded", function() {

    const categoryOptions = document.querySelectorAll('.category-option');
    const priceCategory = document.getElementById('price_category');
    const priceAccompanist = document.getElementById('price_accompanist');
    const total = document.getElementById('total');
    const textTotal = document.getElementById('text_total');
    const accompanist = document.getElementById('accompanist');
    const dvAccompanist = document.getElementById('dv_accompanist');
    const accompanistName = document.getElementById('accompanist_name');
    const accompanistTypeDocument = document.getElementById('accompanist_typedocument');
    const accompanistNumDocument = document.getElementById('accompanist_numdocument');
    const accompanistPhone = document.getElementById('accompanist_phone');
    const accompanistSolapin = document.getElementById('accompanist_solapin');
    const dvSpecialCode = document.getElementById('dv_special_code');
    const specialCode = document.getElementById('special_code');


    function updateCategorySelection(updatePrice = false) {
        const selectedOption = document.querySelector('.category-option:checked');
        document.querySelectorAll('.category-row').forEach(row => {
            row.classList.toggle('is-selected', Boolean(row.querySelector('.category-option:checked')));
        });

        if (selectedOption && selectedOption.dataset.usesSpecialCode === '1') {
            dvSpecialCode.classList.remove('d-none');
            specialCode.setAttribute('required', 'required');
        } else {
            dvSpecialCode.classList.add('d-none');
            specialCode.value = '';
            specialCode.removeAttribute('required');
        }

        if (updatePrice && selectedOption) {
            priceCategory.value = selectedOption.dataset.catprice || 0;
            updateTotal();
        }
    }

    categoryOptions.forEach(option => option.addEventListener('change', () => updateCategorySelection(true)));
    document.querySelectorAll('.category-row').forEach(row => row.addEventListener('click', event => {
        if (event.target.closest('input, label')) return;
        const option = row.querySelector('.category-option');
        if (option) {
            option.checked = true;
            option.dispatchEvent(new Event('change'));
        }
    }));

    // sumar los valores de priceCategory y priceAccompanist
    function updateTotal() {
        const categoryValue = parseFloat(priceCategory.value) || 0;
        const accompanistValue = parseFloat(priceAccompanist.value) || 0;
        let coursesValue = 0;
        document.querySelectorAll('.course-option').forEach(option => {
            option.closest('.course-card').classList.toggle('is-selected', option.checked);
            if (option.checked) coursesValue += parseFloat(option.dataset.coursePrice) || 0;
        });
        const coursesTotal = document.getElementById('text_courses_total');
        if (coursesTotal) coursesTotal.textContent = coursesValue.toFixed(2);
        let toursValue = 0;
        document.querySelectorAll('.tour-option').forEach(option => { const card=option.closest('[data-tour-card]'); const companion=card.querySelector('.tour-companion-option'); card.classList.toggle('border-primary',option.checked); card.querySelector('.tour-companion-control').classList.toggle('d-none',!option.checked); if(!option.checked) companion.checked=false; const fields=card.querySelector('.tour-companion-fields'); fields.classList.toggle('d-none',!option.checked||!companion.checked); fields.querySelectorAll('input,select').forEach(field=>field.required=option.checked&&companion.checked); if(option.checked){ toursValue+=parseFloat(option.dataset.tourPrice)||0; if(companion.checked)toursValue+=parseFloat(companion.dataset.companionPrice)||0; } });
        const toursTotal=document.getElementById('text_tours_total'); if(toursTotal)toursTotal.textContent=toursValue.toFixed(2);
        const totalValue = categoryValue + accompanistValue + coursesValue + toursValue;
        total.value = totalValue;
        textTotal.innerHTML = totalValue.toFixed(2);

        const concepts = [];
        if (document.querySelector('.category-option:checked')) concepts.push('categoría');
        if (accompanist && accompanist.checked) concepts.push('acompañante');
        const selectedCoursesCount = document.querySelectorAll('.course-option:checked').length;
        if (selectedCoursesCount === 1) concepts.push('1 curso');
        if (selectedCoursesCount > 1) concepts.push(selectedCoursesCount + ' cursos');
        const selectedToursCount=document.querySelectorAll('.tour-option:checked').length;
        if(selectedToursCount===1) concepts.push('1 tour');
        if(selectedToursCount>1) concepts.push(selectedToursCount+' tours');
        const conceptsText = document.getElementById('totalConceptsText');
        conceptsText.textContent = concepts.length ? 'Incluye: ' + concepts.join(', ') + '.' : 'Selecciona los conceptos de la inscripción.';
    }

    priceCategory.addEventListener('input', updateTotal);
    priceAccompanist.addEventListener('input', updateTotal);
    document.querySelectorAll('.course-option').forEach(option => option.addEventListener('change', updateTotal));
    document.querySelectorAll('.tour-option,.tour-companion-option').forEach(option => option.addEventListener('change', updateTotal));

    //if accompanist is checked
    if(accompanist){
        accompanist.addEventListener('change', (event) => {
        if (event.target.checked) {
            dvAccompanist.classList.remove('d-none');
            accompanistName.setAttribute('required', 'required');
            accompanistTypeDocument.setAttribute('required', 'required');
            accompanistNumDocument.setAttribute('required', 'required');
            accompanistPhone.setAttribute('required', 'required');
            accompanistSolapin.setAttribute('required', 'required');
        } else {
            dvAccompanist.classList.add('d-none');
            priceAccompanist.value = 0;
            updateTotal();
            accompanistName.removeAttribute('required');
            accompanistTypeDocument.removeAttribute('required');
            accompanistNumDocument.removeAttribute('required');
            accompanistPhone.removeAttribute('required');
            accompanistSolapin.removeAttribute('required');
        }
    });
    }


    //if click change_document_file
    const changeDocumentFile = document.getElementById('change_document_file');
    const dvDocumentFileUpload = document.getElementById('dv_document_file_upload');
    const inputDocumentFile = document.getElementById('document_file');

    if (changeDocumentFile) changeDocumentFile.addEventListener('click', (event) => {
        event.preventDefault();
        dvDocumentFileUpload.classList.toggle('d-none');
        if(!dvDocumentFileUpload.classList.contains('d-none')){
            inputDocumentFile.value = '';
        }
    });

    //if click change_voucher_file
    const changeVoucherFile = document.getElementById('change_voucher_file');
    const dvVoucherFile = document.getElementById('dv_voucher_file');
    const inputVoucherFile = document.getElementById('voucher_file');

    if (changeVoucherFile) changeVoucherFile.addEventListener('click', (event) => {
        event.preventDefault();
        dvVoucherFile.classList.toggle('d-none');
        if(!dvVoucherFile.classList.contains('d-none')){
            inputVoucherFile.value = '';
        }
    });

    const country = document.getElementById('country');
    const invoiceNo = document.getElementById('invoice_no');
    const invoiceYes = document.getElementById('invoice_yes');
    const invoiceContainer = document.getElementById('dv_invoice');
    const invoiceInfo = document.getElementById('dv_invoice_info');
    const invoiceFields = invoiceInfo.querySelectorAll('input');

    function updateInvoiceFields() {
        const isPeru = country.value === 'Perú';
        invoiceContainer.classList.toggle('d-none', !isPeru);
        invoiceYes.disabled = !isPeru;
        if (!isPeru) invoiceNo.checked = true;
        const showFields = isPeru && invoiceYes.checked;
        invoiceInfo.classList.toggle('d-none', !showFields);
        invoiceFields.forEach(field => {
            field.required = showFields;
            if (!showFields && !isPeru) field.value = '';
        });
    }

    country.addEventListener('change', updateInvoiceFields);
    document.querySelectorAll('input[name="invoice"]').forEach(radio => radio.addEventListener('change', updateInvoiceFields));

    function updatePaymentChoiceStyle() {
        document.querySelectorAll('.payment-choice').forEach(choice => {
            choice.classList.toggle('is-selected', Boolean(choice.querySelector('input:checked')));
        });
    }
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => radio.addEventListener('change', updatePaymentChoiceStyle));

    updateCategorySelection();
    updateInvoiceFields();
    updatePaymentChoiceStyle();
    if (accompanist) accompanist.dispatchEvent(new Event('change'));
    updateTotal();

    document.getElementById('editInscriptionForm').addEventListener('submit', function () {
        const button = document.getElementById('btnUpdateInscription');
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';
    });

});

</script>
