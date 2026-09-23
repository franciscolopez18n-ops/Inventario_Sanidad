@extends('layout.app')

@section('head-extra')
    <x-csrf-meta />
@endsection

@section('title', 'Gestión de material')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/confirm-dialog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/search-bar-tool.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/fillable-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/pagination.css') }}">
@endpush

@section('content')

<x-loader />

<x-confirm-dialog
    :id="'delete-cd'"
    :message="'¿Estás seguro de que deseas eliminar el material seleccionado?'"
/>

<h2>Gestión de material</h2>

<x-search-bar-tool
    :options="[
        ['label' => 'Nombre', 'path' => 'name'],
        ['label' => 'Descripción', 'path' => 'description'],
    ]"
/>
<x-fillable-table
    :table-id="'materials-management-table'"
    :columns="['Nombre', 'Descripción', 'Imagen']"
    :actions-colspan="2"
/>
<x-pagination />

@endsection

@push('scripts')
    <script type="application/json" id="materials-data">@json($materials)</script>
    <script type="module" src="{{ asset('js/materials/manage/table.js') }}"></script>
@endpush