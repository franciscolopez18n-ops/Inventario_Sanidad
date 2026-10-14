@extends('layout.app')

@section('title', 'Editar almacenamiento')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/materials/materials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/materials/manage/subtract.css') }}">
@endpush

@section('content')
<h1>Editar almacenamiento para {{ $material->name }}</h1>

<form
    action="{{ route('materials.manage.subtract', $material->material_id) }}" 
    method="POST"
    enctype="multipart/form-data" 
    class="material-form form-inline"
>
    @csrf
    
    <div class="storage-grid storage-grid--centered">
        @foreach ($storages as $storage)
            <fieldset class="panel">
                @php
                    $s = $storage->storage;
                @endphp
                
                <legend>{{ display_name($s, DisplayCategory::STORAGE) }}</legend>

                <div class="form-group">
                    <label for="{{ $s }}_use_units">Unidades en Uso</label>
                    <input id="{{ $s }}_use_units" type="number" value="{{ $storage->units }}" readonly>
                </div>

                <div class="form-group">
                    <label for="{{ $s }}_subtract_units">Unidades a restar</label>
                    <input id="{{ $s }}_subtract_units" type="number" name="{{ $s }}[subtract_units]" value="0" class="@error("$s.subtract_units") input-error @enderror">
                </div>
                @error("$s.subtract_units")
                    <small class="input-error-msg">{{ $message }}</small>
                @enderror
            </fieldset>
        @endforeach
    </div>

    <div class="form-actions">
        <input type="submit" value="Actualizar" class="btn btn-success">
        <a href="{{ route('materials.manage.index') }}" class="btn btn-outline">Volver al listado</a>
    </div>
</form>
@endsection