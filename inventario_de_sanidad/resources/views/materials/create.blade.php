@extends('layout.app')

@section('title', 'Alta de materiales')

@push('styles')
    
<link rel="stylesheet" href="{{ asset('css/tables.css') }}">
<link rel="stylesheet" href="{{ asset('css/materials/materials.css') }}">
    
@endpush

@section('content')
<div class="material-form-wrapper">
    <h1>Alta de Materiales</h1>

    {{-- Boton de agregar a la cesta --}}
    <div class="basket-toggle">
        <button id="toggleBasketBtn" class="btn btn-outline btn-notifications" type="button">
            <i class="fa-solid fa-basket-shopping"></i>
        </button>
    </div>

    {{-- Formulario para agregar a la cesta --}}
    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" class="material-form" name="form">
        @csrf

        <div class="form-group">
            <input type="text" name="name" placeholder="Nombre del material">
        </div>

        <div class="form-group">
            <textarea name="description" rows="3" placeholder="Descripción del material"></textarea>
        </div>

        <div class="form-group">
            <p>Localización</p>

            <input type="radio" id="cae" name="storage" value="CAE">
            <label for="cae">CAE</label><br>

            <input type="radio" id="odontology" name="storage" value="odontology">
            <label for="odontology">Odontología</label><br>

            <input type="radio" id="ambos" name="storage" value="ambos">
            <label for="ambos">Ambos</label><br>
        </div>

        {{-- Uso --}}
        <fieldset class="fieldset">
            <legend>Uso</legend>
            <div class="form-grid-5">
                <input type="number" name="units_use" placeholder="Cantidad">
                <input type="number" name="min_units_use" placeholder="Cantidad mínima">
                <input type="number" name="cabinet_use" placeholder="Armario">
                <input type="number" name="shelf_use" placeholder="Balda">
                <input type="number" name="drawer" placeholder="Cajón">
            </div>
        </fieldset>

        {{-- Reserva --}}
        <fieldset class="fieldset">
            <legend>Reserva</legend>
            <div class="form-grid-4">
                <input type="number" name="units_reserve" placeholder="Cantidad">
                <input type="number" name="min_units_reserve" placeholder="Cantidad mínima">
                <input type="text" name="cabinet_reserve" placeholder="Armario">
                <input type="number" name="shelf_reserve" placeholder="Balda">
            </div>
        </fieldset>

        <div class="form-group file-upload">
            {{-- Botón de subir imagen --}}
            <label for="image" class="btn btn-primary">Subir Imagen</label>
            <input type="file" name="image" id="image" class="btn btn-primary file-upload-input" onchange="previewImage(event, '#imgPreview')">
            
            {{-- Imagen previsualizada --}}
            <img id="imgPreview" src="" alt="">
            <span id="file-name" class="file-name-display">Ningún archivo seleccionado</span>
        </div>

        {{-- Botón de añadir --}}
        <div class="form-actions">
            <input type="button" value="Añadir" class="btn btn-primary" name="add">
        </div>

        {{-- Mensaje de éxito --}}
        <div id="success-message" class="success hidden"></div>

        {{-- Botón de alta --}}
        <input type="submit" value="Alta" class="btn btn-success">
    </form>

    {{-- Alertas flash --}}
    <x-alerts />

    {{-- Cesta --}}
    <div class="basket-section hidden">
        <h4 class="basket-title">Cesta de Materiales</h4>
        
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">Nombre</th>
                        <th rowspan="2">Descripción</th>
                        <th rowspan="2">Localización</th>
                        <th colspan="5">Uso</th>
                        <th colspan="4">Reserva</th>
                        <th rowspan="2">Imagen</th>
                        <th rowspan="2"></th>
                    </tr>
                    <tr>
                        <th>Cant.</th><th>Mín</th><th>Armario</th><th>Balda</th><th>Cajón</th>
                        <th>Cant.</th><th>Mín</th><th>Armario</th><th>Balda</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/previewImage.js') }}"></script>
    <script src="{{ asset('js/material.js') }}"></script>
@endpush