@if($errors->any())
    <div class="alert alert-danger">
        <strong>Revisa los datos ingresados.</strong>
        <ul class="mb-0 mt-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <label for="name" class="form-label">Nombre del curso <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $course->name ?? '') }}" maxlength="255" required>
    </div>
    <div class="col-md-4">
        <label for="price" class="form-label">Precio (US$) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $course->price ?? '') }}" min="0" step="0.01" required>
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Descripción</label>
        <textarea class="form-control" id="description" name="description" rows="3" maxlength="2000">{{ old('description', $course->description ?? '') }}</textarea>
    </div>
    <div class="col-md-3">
        <label for="course_date" class="form-label">Fecha</label>
        <input type="date" class="form-control" id="course_date" name="course_date" value="{{ old('course_date', isset($course) && $course->course_date ? $course->course_date->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-2">
        <label for="start_time" class="form-label">Hora de inicio</label>
        <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', isset($course) ? substr((string) $course->start_time, 0, 5) : '') }}">
    </div>
    <div class="col-md-2">
        <label for="end_time" class="form-label">Hora de término</label>
        <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', isset($course) ? substr((string) $course->end_time, 0, 5) : '') }}">
    </div>
    <div class="col-md-5">
        <label for="location" class="form-label">Lugar o sala</label>
        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $course->location ?? '') }}" maxlength="255">
    </div>
    <div class="col-md-6">
        <label for="capacity" class="form-label">Cupos</label>
        <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $course->capacity ?? '') }}" min="1" placeholder="Sin límite">
        <small class="text-muted">Déjalo vacío si no tiene límite.</small>
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
        <select class="form-select" id="status" name="status" required>
            <option value="active" @if(old('status', $course->status ?? 'active') === 'active') selected @endif>Activo</option>
            <option value="inactive" @if(old('status', $course->status ?? 'active') === 'inactive') selected @endif>Inactivo</option>
        </select>
    </div>
</div>
