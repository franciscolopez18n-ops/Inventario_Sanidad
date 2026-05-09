@extends('layout.app')

@section('title', 'Edicion de materiales')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/storages/editMaterialTeacher.css') }}">
@endpush

@section('content')
<div class="">
    <div class="edit-container">
        @php
            $useRecord = $material->storage->where('storage_type', 'use')->where('storage',$currentLocation)->first();
        @endphp
        <form action="{{ route('storages.subtract.teacher', [$material->material_id, $currentLocation]) }}" method="POST">
            @csrf

            <h1>Editar Almacenamiento para: {{ $material->name }} / {{ $currentLocation == "CAE" ? "CAE" : "Odontología" }}</h1>

            <div class="form-group">
                <label for="use_units">Unidades en Uso</label>
                <input id="use_units" type="number" name="use_units" class="form-control input-gray" value="{{ $useRecord->units ?? '0' }}" readonly>
            </div>
            @error('use_units')
                <div class="alert alert-error alert-form">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="subtract_units">Unidades a restar</label>
                <input id="subtract_units" type="number" placeholder="Cantidad a restar" name="subtract_units" class="form-control" value="0" min="0" max="{{ $useRecord->units ?? '0' }}" required>
            </div>
            
            <br>
            <div class="form-actions">
                <input type="submit" value="Actualizar" class="btn btn-success">
                <a href="{{ route('storages.updateView') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Volver al listado </a>
            </div>
            <br>
        </form>

        <!-- Alertas flash -->
        <x-alerts />

        @error('subtract_units')
            <p class="alert alert-error alert-form">{{ $message }}</p>
        @enderror
    </div>
</div>
@endsection