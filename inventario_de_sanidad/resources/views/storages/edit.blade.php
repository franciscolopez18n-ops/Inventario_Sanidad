@extends('layout.app')

@section('title', 'Edicion de materiales')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/storages/editStorage.css') }}">
@endpush

@section('content')
<div class="">
    <form action="{{ route('storages.updateBatch', [$material->material_id, $currentLocation]) }}" method="POST">
        @csrf

        <h1>Editar Almacenamiento para: <span class="material-name">{{ $material->name }}</span></h1>

        <div class="location-container">
            <p for="storage">Localización:</p>
            <p>{{ $currentLocation == "CAE" ? "CAE" : "Odontología" }}</p>
        </div>

        <fieldset>
            <legend>Datos para Uso</legend>

            @php
                $useRecord = $material->storage->where('storage_type', 'use')->where('storage', $currentLocation)->first();
            @endphp

            <div class="form-grid">
                <div class="">
                    <label>Cantidad</label>
                    <input type="number" name="use_units" class="form-control" value="{{ $useRecord->units ?? '0' }}" min="0" required>
                    @error('use_units')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    <label>Cantidad Mínima</label>
                    <input type="number" name="use_min_units" class="form-control" value="{{ $useRecord->min_units ?? '0' }}" min="0" required>
                    @error('use_min_units')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    <label>Armario</label>
                    <input type="number" name="use_cabinet" class="form-control" value="{{ $useRecord->cabinet ?? '0' }}" min="0" required>
                    @error('use_cabinet')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    <label>Balda</label>
                    <input type="number" name="use_shelf" class="form-control" value="{{ $useRecord->shelf ?? '0' }}" min="0" required>
                    @error('use_shelf')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    <label>Cajón</label>
                    <input type="number" name="drawer" class="form-control" value="{{ $useRecord->drawer ?? '0' }}" min="0" required>
                    @error('drawer')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Datos para Reserva</legend>

            @php
                $reserveRecord = $material->storage->where('storage_type', 'reserve')->where('storage', $currentLocation)->first();
            @endphp

            <div class="form-grid">
                <div class="">
                    <label>Cantidad</label>
                    <input type="number" name="reserve_units" class="form-control" value="{{ $reserveRecord->units ?? '' }}" min="0" required>
                    @error('reserve_units')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    <label>Cantidad Mínima</label>
                    <input type="number" name="reserve_min_units" class="form-control" value="{{ $reserveRecord->min_units ?? '' }}" min="0" required>
                    @error('reserve_min_units')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    <label>Armario</label>
                    <input type="text" name="reserve_cabinet" class="form-control" value="{{ $reserveRecord->cabinet ?? '' }}" required>
                    @error('reserve_cabinet')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    <label>Balda</label>
                    <input type="number" name="reserve_shelf" class="form-control" value="{{ $reserveRecord->shelf ?? '' }}" min="0" required>
                    @error('reserve_shelf')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    
                </div>
            </div>
        </fieldset>
        
        <div>
            <br>
            <input type="checkbox" id="onlyReserve" name="onlyReserve" value="1">
            <label for="onlyReserve">Actualizar solamente reserva</label>
        </div>

        <br>
        <div class="form-actions">
            <input type="submit" value="Actualizar Almacenamiento" class="btn btn-primary">
            <a href="{{ route('storages.updateView') }}" class="btn btn-danger">Cancelar</a>
        </div>
        <br>
    </form>

    <!-- Alertas flash -->
    <x-alerts />
    
</div>
@endsection

