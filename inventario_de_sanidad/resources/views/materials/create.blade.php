@extends('layout.app')

@section('title', 'Alta de materiales')

@push('styles')
    
<link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/fillable-table.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/view-toggle.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/image-preview.css') }}">
<link rel="stylesheet" href="{{ asset('css/materials/forms.css') }}">
<link rel="stylesheet" href="{{ asset('css/materials/create.css') }}">

@endpush

@section('content')

<x-loader />

<div class="title-with-toggle">
    <x-view-toggle
        :view-btns="[
            ['id' => 'form-view-btn', 'extra_class' => 'btn-toggle-text', 'text' => 'Alta de materiales'],
            ['id' => 'batch-view-btn', 'extra_class' => 'btn-toggle-text', 'text' => 'Lote pendiente'],
        ]"
    />

    <h1>Alta de materiales</h1>
</div>

{{-- Formulario para agregar al lote --}}
<form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" class="material-form" name="form">
    @csrf

    <div class="form-group">
        <input type="text" data-client-name="name" placeholder="Nombre del material">
    </div>

    <div class="form-group">
        <textarea data-client-name="description" rows="3" placeholder="Descripción del material"></textarea>
    </div>

    <div class="form-group">
        <p>Localización</p>

        <input type="radio" id="cae" data-client-name="storage" value="CAE">
        <label for="cae">CAE</label><br>

        <input type="radio" id="odontology" data-client-name="storage" value="odontology">
        <label for="odontology">Odontología</label><br>

        <input type="radio" id="ambos" data-client-name="storage" value="ambos">
        <label for="ambos">Ambos</label><br>
    </div>

    {{-- Uso --}}
    <fieldset class="fieldset">
        <legend>Uso</legend>

        <div class="form-grid-5">

            <div class="field">
                <input type="number" data-client-name="units_use" placeholder="Cantidad">
            </div>

            <div class="field">
                <input type="number" data-client-name="min_units_use" placeholder="Cantidad mínima">
            </div>

            <div class="field">
                <input type="number" data-client-name="cabinet_use" placeholder="Armario">
            </div>

            <div class="field">
                <input type="number" data-client-name="shelf_use" placeholder="Balda">
            </div>

            <div class="field">
                <input type="number" data-client-name="drawer_use" placeholder="Cajón">
            </div>

        </div>
    </fieldset>

    {{-- Reserva --}}
    <fieldset class="fieldset">
        <legend>Reserva</legend>

        <div class="form-grid-4">

            <div class="field">
                <input type="number" data-client-name="units_reserve" placeholder="Cantidad">
            </div>

            <div class="field">
                <input type="number" data-client-name="min_units_reserve" placeholder="Cantidad mínima">
            </div>

            <div class="field">
                <input type="text" data-client-name="cabinet_reserve" placeholder="Armario">
            </div>

            <div class="field">
                <input type="number" data-client-name="shelf_reserve" placeholder="Balda">
            </div>

        </div>
    </fieldset>

    <x-image-preview
        :form-group="'form-group'"
        :label-text="'Subir imagen'"
        :is-client-field="true"
    />

    <div class="form-actions">
        {{-- Botón de añadir --}}
        <button type="button" name="add" class="btn btn-primary">
            Añadir
        </button>
        
        {{-- Botón de alta (submit real) --}}
        <button type="submit" id="btn-submit-create" class="btn btn-success">
            Alta
        </button>
    </div>
</form>

{{-- Lote --}}
<div class="batch-section hidden">
    <h4 class="batch-title">Lote de materiales</h4>
    
    <x-fillable-table
        :wrapper-id="'materials-batch-table-wrapper'"
        :table-id="'materials-batch-table'"
        :columns="[
            [
                ['column' => 'Nombre', 'rowspan' => 2],
                ['column' => 'Descripción', 'rowspan' => 2],
                ['column' => 'Localización', 'rowspan' => 2],
                ['column' => 'Uso', 'colspan' => 5],
                ['column' => 'Reserva', 'colspan' => 4],
                ['column' => 'Imagen', 'rowspan' => 2],
            ],
            [
                'Cant.', 'Mín', 'Armario', 'Balda', 'Cajón',
                'Cant.', 'Mín', 'Armario', 'Balda',
            ],
        ]"
        :num-actions="1"
    />
</div>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/materials/create.js') }}"></script>
@endpush