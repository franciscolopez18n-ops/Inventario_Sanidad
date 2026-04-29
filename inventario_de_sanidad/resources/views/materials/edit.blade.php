@extends('layout.app')

@section('title', 'Editar Material')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/materials/materials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
@endpush

@section('content')
<div class="material-form-wrapper">
    <h1>Editar Material</h1>

    <form action="{{ route('materials.update', $material->material_id) }}" method="POST" enctype="multipart/form-data" class="material-form">
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

        {{-- Alertas flash --}}
        <x-alerts />

        <div class="form-actions">
            <input type="submit" value="Actualizar" class="btn btn-success">
            <br><br><br>
            <a href="{{ route('materials.index') }}" class="btn btn-outline">Volver al listado</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/previewImage.js') }}"></script>
@endpush