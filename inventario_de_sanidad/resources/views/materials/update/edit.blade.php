@extends('layout.app')

@section('title', 'Editar Material')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/materials/materials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
@endpush

@section('content')
<div class="material-form-wrapper">
    <h1>Editar Material</h1>

    <form
        action="{{ route('materials.update.submit', $material->material_id) }}" 
        method="POST"
        enctype="multipart/form-data" 
        class="material-form"
    >
        @csrf

        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name', $material->name) }}">
            @error('name')
                <div class="alert alert-error alert-form">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea name="description" id="description" rows="3">{{ old('description', $material->description) }}</textarea>
            @error('description')
                <div class="alert alert-error alert-form">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group file-upload">
            <label for="image" class="btn btn-primary">Cambiar Imagen <i class="fa-solid fa-image"></i></label>
            <input type="file" name="image" id="image" class="file-upload-input" onchange="previewImage(event, '#imgPreview')">
            <img id="imgPreview"
                src="{{ asset($material->image_path ? 'storage/' . $material->image_path : 'img/no_image.jpg') }}"
                alt="Previsualización"
                style="max-width: 150px; display: block; margin-top: 10px;">
            @error('image')
                <div class="alert alert-error alert-form">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            @foreach ($storages as $storage)
                <fieldset>
                    <legend>{{ $storage->storage === 'CAE' ? 'CAE' : 'Odontología' }}</legend>

                    @php
                        $useRecord = $storage->storageUse;
                        $reserveRecord = $storage->storageReserve;
                        $s = $storage->storage;
                    @endphp

                    <div class="storage-block">
                        <p><strong>Uso</strong></p>
                        <div class="form-grid">
                            <div>
                                <label>Cantidad</label>
                                <input type="number" name="{{ $s }}[use_units]" value="{{ old("$s.use_units", $useRecord->units ?? 0) }}" min="0" required>
                                @error("$s.use_units") <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label>Cantidad Mínima</label>
                                <input type="number" name="{{ $s }}[use_min_units]" value="{{ old("$s.use_min_units", $useRecord->min_units ?? 0) }}" min="0" required>
                                @error("$s.use_min_units") <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label>Armario</label>
                                <input type="number" name="{{ $s }}[use_cabinet]" value="{{ old("$s.use_cabinet", $useRecord->cabinet ?? 0) }}" min="1" required>
                                @error("$s.use_cabinet") <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label>Balda</label>
                                <input type="number" name="{{ $s }}[use_shelf]" value="{{ old("$s.use_shelf", $useRecord->shelf ?? 0) }}" min="1" required>
                                @error("$s.use_shelf") <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label>Cajón</label>
                                <input type="number" name="{{ $s }}[use_drawer]" value="{{ old("$s.use_drawer", $useRecord->drawer ?? 0) }}" min="1" required>
                                @error("$s.use_drawer") <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <p><strong>Reserva</strong></p>
                        <div class="form-grid">
                            <div>
                                <label>Cantidad</label>
                                <input type="number" name="{{ $s }}[reserve_units]" value="{{ old("$s.reserve_units", $reserveRecord->units ?? 0) }}" min="0" required>
                                @error("$s.reserve_units") <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label>Cantidad Mínima</label>
                                <input type="number" name="{{ $s }}[reserve_min_units]" value="{{ old("$s.reserve_min_units", $reserveRecord->min_units ?? 0) }}" min="0" required>
                                @error("$s.reserve_min_units") <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label>Armario</label>
                                <input type="text" name="{{ $s }}[reserve_cabinet]" value="{{ old("$s.reserve_cabinet", $reserveRecord->cabinet ?? '') }}" required>
                                @error("$s.reserve_cabinet") <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label>Balda</label>
                                <input type="number" name="{{ $s }}[reserve_shelf]" value="{{ old("$s.reserve_shelf", $reserveRecord->shelf ?? 0) }}" min="1" required>
                                @error("$s.reserve_shelf") <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <input type="checkbox" id="onlyReserve_{{ $s }}" name="{{ $s }}[onlyReserve]" value="1" {{ old("$s.onlyReserve") ? 'checked' : '' }}>
                        <label for="onlyReserve_{{ $s }}">Actualizar solamente reserva</label>
                    </div>

                </fieldset>
            @endforeach
        </div>

        <div class="form-actions">
            <input type="submit" value="Actualizar" class="btn btn-success">
            <br><br><br>
            <a href="{{ route('materials.update.index') }}" class="btn btn-outline">Volver al listado</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/previewImage.js') }}"></script>
@endpush
