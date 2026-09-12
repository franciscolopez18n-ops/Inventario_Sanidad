@extends('layout.app')

@section('head-extra')
    <x-csrf-meta />
@endsection

@section('title', 'Gestión de material')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/paginated-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/confirm-dialog.css') }}">
@endpush

@section('content')

<x-loader />

<!-- Dialogo para confirmar eliminación de material -->
<dialog id="confirmation">
    <p>¿Estás seguro de que deseas eliminar el material seleccionado?</p>
    <input type="button" class="btn btn-success" value="Aceptar" id="acept">
    <input type="button" class="btn btn-danger" value="Cancelar" id="cancel">
</dialog>

<div class="content-wrapper">
    <h2>Gestión de material</h2>

    <x-search-bar
        :options="[
            ['label' => 'Nombre', 'field' => 'name'],
            ['label' => 'Descripción', 'field' => 'description'],
        ]"
    />
    <x-paginated-table
        :columns="['Nombre', 'Descripción', 'Imagen']"
        :actions-colspan="2"
    />
</div>
@endsection

@push('scripts')
    <script type="application/json" id="materials-data">@json($materials)</script>
    <script src="{{ asset('js/components/loader.js') }}"></script>
    <script src="{{ asset('js/components/confirmDialog.js') }}"></script>
    <script type="module" src="{{ asset('js/materials/manage/table.js') }}"></script>
    <script src="{{ asset('js/components/filterToggle.js') }}"></script> 
@endpush