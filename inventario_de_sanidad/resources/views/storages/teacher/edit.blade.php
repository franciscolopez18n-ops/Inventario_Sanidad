@extends('layout.app')

@section('title', 'Edicion de materiales')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/storages/editMaterialTeacher.css') }}">
@endpush

@section('content')
<div class="">
    <div class="edit-container">
        @php
            $useRecord = $material->storageUse()->where('storage', $currentLocation)->first();
        @endphp
        <form action="{{ route('storages.subtract.teacher', [$material->material_id, $currentLocation]) }}" method="POST">
            @csrf

            <h1>Editar Almacenamiento para: {{ $material->name }} / {{ display_name($currentLocation, DisplayCategory::STORAGE) }}</h1>

            <div class="form-group">
                <label for="use_units">Unidades en Uso</label>
                <input id="use_units" type="number" value="{{ $useRecord->units ?? '0' }}" readonly>
            </div>

            <div class="form-group">
                <label for="subtract_units">Unidades a restar</label>
                <input id="subtract_units" type="number" placeholder="Cantidad a restar" name="subtract_units" value="0" min="0" max="{{ $useRecord->units ?? '0' }}" class="@error('subtract_units') input-error @enderror">
            </div>
            @error('subtract_units')
                <small class="input-error-msg">{{ $message }}</small>
            @enderror
            
            <br>
            <div class="form-actions">
                <input type="submit" value="Actualizar" class="btn btn-success">
                <a href="{{ route('storages.updateView') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Volver al listado </a>
            </div>
            <br>
        </form>
    </div>
</div>
@endsection