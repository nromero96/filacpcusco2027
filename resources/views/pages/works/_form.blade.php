@php
    $editing = isset($work);
    $profile = $editing ? $work->user : Auth::user();
    $defaultFirstName = mb_strtoupper(trim((string) $profile->name), 'UTF-8');
    $defaultLastName = mb_strtoupper(trim(implode(' ', array_filter([$profile->lastname, $profile->second_lastname]))), 'UTF-8');
    $defaultWhatsapp = trim(($profile->whatsapp_code ?? '').' '.($profile->whatsapp_number ?? ''));
@endphp

<style>
    .work-form-card { border: 1px solid #e6eaf0; border-radius: 16px; box-shadow: 0 10px 28px rgba(31,45,61,.07); overflow: hidden; }
    .work-form-header { display: flex; align-items: center; gap: .8rem; padding: 1.15rem 1.25rem; border-top: 4px solid #882c31; border-bottom: 1px solid #edf0f5; background: linear-gradient(180deg, #fff 0%, #fcf8f8 100%); }
    .work-form-header-icon { width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; border-radius: 12px; color: #882c31; background: #f4e8e9; }
    .work-form-header h4 { color: #3b1f21; }
    .work-form-header p { color: #7a686a; }
    .work-form-section { padding: 1.25rem; }
    .work-form-section + .work-form-section { border-top: 1px solid #edf0f5; }
    .work-form-section-title { color: #1b2559; font-weight: 700; margin-bottom: 1rem; }
    .work-form-card .form-control, .work-form-card .form-select { min-height: 44px; border-radius: 10px; border-color: #dfe5ef; }
    .work-form-card textarea.form-control { min-height: 220px; }
    .work-form-card .form-control:focus, .work-form-card .form-select:focus { border-color: #4361ee; box-shadow: 0 0 0 3px rgba(67,97,238,.12); }
    .work-name-uppercase { text-transform: uppercase; }
    .work-word-counter { color: #888ea8; font-size: .8rem; }
    .work-word-counter.is-invalid { color: #e7515a; font-weight: 700; }
</style>

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Revisa los datos ingresados.</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction }}" method="POST" class="card work-form-card" id="workForm">
    @csrf
    @if($editing)
        @method('PUT')
    @endif

    <div class="work-form-header">
        <span class="work-form-header-icon">
            <svg width="23" height="23" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6"></path><path d="M8 13h8M8 17h6"></path></svg>
        </span>
        <div>
            <h5 class="mb-1">{{ $editing ? 'Editar trabajo' : 'Registro de trabajo' }}</h5>
            <small class="mb-0">Completa la información del autor y el resumen del tema libre.</small>
        </div>
    </div>

    <div class="work-form-section">
        <h6 class="work-form-section-title">Datos de contacto y actividad profesional</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label text-muted mb-0">Nombre</label>
                <input type="text" name="first_name" id="first_name" class="form-control work-name-uppercase" value="{{ old('first_name', $work->first_name ?? $defaultFirstName) }}" maxlength="120" required>
                <small class="text-muted">Se guardará automáticamente en mayúsculas.</small>
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label text-muted mb-0">Apellidos</label>
                <input type="text" name="last_name" id="last_name" class="form-control work-name-uppercase" value="{{ old('last_name', $work->last_name ?? $defaultLastName) }}" maxlength="180" required>
                <small class="text-muted">Se guardará automáticamente en mayúsculas.</small>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label text-muted mb-0">Correo electrónico</label>
                <input type="email" id="email" class="form-control bg-light" value="{{ $profile->email }}" readonly aria-readonly="true">
                <small class="text-muted">Correo asociado a la cuenta del participante.</small>
            </div>
            <div class="col-md-6">
                <label for="whatsapp_phone" class="form-label text-muted mb-0">Teléfono de contacto WhatsApp</label>
                <input type="tel" name="whatsapp_phone" id="whatsapp_phone" class="form-control" value="{{ old('whatsapp_phone', $work->whatsapp_phone ?? $defaultWhatsapp) }}" placeholder="51 999 999 999" maxlength="30" required>
                <small class="text-muted">Incluye el código de país y el número. El signo + es opcional.</small>
            </div>
            <div class="col-md-6">
                <label for="current_position" class="form-label text-muted mb-0">Cargo o posición actual</label>
                <input type="text" name="current_position" id="current_position" class="form-control" value="{{ old('current_position', $work->current_position ?? '') }}" maxlength="255" required>
            </div>
            <div class="col-12">
                <label for="institution" class="form-label text-muted mb-0">Institución o lugar donde desarrolla su actividad profesional</label>
                <input type="text" name="institution" id="institution" class="form-control" value="{{ old('institution', $work->institution ?? $profile->workplace) }}" maxlength="255" required>
            </div>
            <div class="col-md-6">
                <label for="city" class="form-label text-muted mb-0">Ciudad</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $work->city ?? $profile->city) }}" maxlength="120" required>
            </div>
            <div class="col-md-6">
                <label for="country" class="form-label text-muted mb-0">País</label>
                <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $work->country ?? $profile->country) }}" maxlength="120" required>
            </div>
        </div>
    </div>

    <div class="work-form-section">
        <h6 class="work-form-section-title">Información del trabajo</h6>
        <div class="row g-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-end gap-2 mb-1">
                    <label for="title" class="form-label text-muted mb-0">Título del trabajo</label>
                    <span class="work-word-counter" id="titleCounter">0 / 255 caracteres</span>
                </div>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $work->title ?? '') }}" maxlength="255" required>
            </div>
            <div class="col-md-6">
                <label for="area" class="form-label text-muted mb-0">Área</label>
                <select name="area" id="area" class="form-select" required>
                    <option value="">Selecciona un área</option>
                    @foreach($areas as $area)
                        <option value="{{ $area }}" {{ old('area', isset($work) ? $work->area : '') === $area ? 'selected' : '' }}>{{ $area }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 {{ old('area', $work->area ?? '') === 'OTROS' ? '' : 'd-none' }}" id="areaOtherGroup">
                <label for="area_other" class="form-label">Especifica otra área</label>
                <input type="text" name="area_other" id="area_other" class="form-control" value="{{ old('area_other', $work->area_other ?? '') }}" maxlength="255">
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-end gap-2 mb-1">
                    <label for="abstract" class="form-label text-muted mb-0">Resumen del tema libre (máximo {{ $maxAbstractWords }} palabras)</label>
                    <span class="work-word-counter" id="wordCounter">0 / {{ $maxAbstractWords }} palabras</span>
                </div>
                <textarea name="abstract" id="abstract" class="form-control" rows="10" required>{{ old('abstract', $work->abstract ?? '') }}</textarea>
                <small class="text-muted">Extensión máxima: {{ $maxAbstractWords }} palabras.</small>
            </div>
        </div>
    </div>

    <div class="work-form-section d-flex flex-column-reverse flex-sm-row justify-content-end gap-2">
        <a href="{{ route('works.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary" id="submitWork">{{ $editing ? 'Guardar cambios' : 'Registrar trabajo' }}</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    const area = document.getElementById('area');
    const otherGroup = document.getElementById('areaOtherGroup');
    const other = document.getElementById('area_other');
    const abstract = document.getElementById('abstract');
    const counter = document.getElementById('wordCounter');
    const maxAbstractWords = @json($maxAbstractWords);
    const title = document.getElementById('title');
    const titleCounter = document.getElementById('titleCounter');
    const form = document.getElementById('workForm');
    const submit = document.getElementById('submitWork');

    const updateArea = function () {
        const isOther = area.value === 'OTROS';
        otherGroup.classList.toggle('d-none', !isOther);
        other.required = isOther;
        if (!isOther) other.value = '';
    };
    const updateWords = function () {
        const value = abstract.value.trim();
        const count = value ? value.split(/\s+/u).filter(Boolean).length : 0;
        counter.textContent = `${count} / ${maxAbstractWords} palabras`;
        counter.classList.toggle('is-invalid', count > maxAbstractWords);
        abstract.setCustomValidity(count > maxAbstractWords ? `El resumen no puede superar las ${maxAbstractWords} palabras.` : '');
    };
    const updateTitle = function () {
        titleCounter.textContent = `${title.value.length} / 255 caracteres`;
    };

    firstName.addEventListener('input', function () { this.value = this.value.toLocaleUpperCase('es'); });
    lastName.addEventListener('input', function () { this.value = this.value.toLocaleUpperCase('es'); });
    area.addEventListener('change', updateArea);
    abstract.addEventListener('input', updateWords);
    title.addEventListener('input', updateTitle);
    form.addEventListener('submit', function () {
        if (!form.checkValidity()) return;
        submit.disabled = true;
        submit.textContent = 'Guardando...';
    });
    updateArea();
    updateWords();
    updateTitle();
});
</script>
