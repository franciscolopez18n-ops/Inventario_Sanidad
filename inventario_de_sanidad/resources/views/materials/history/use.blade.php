@extends('layout.app')

@section('title', 'Materiales en uso')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/view-toggle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/search-bar-tool.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/fillable-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('css/materials/history/summary.css') }}">
@endpush

@section('content')

<x-loader />

<div class="title-with-toggle">
    <h1>Materiales en Uso</h1>

    <x-view-toggle 
        :view-btns="[
            ['id' => 'card-view-btn', 'extra_class' => 'btn-notifications', 'icon' => 'fa-solid fa-list-ul'],
            ['id' => 'table-view-btn', 'extra_class' => 'btn-notifications', 'icon' => 'fa-solid fa-table'],
        ]"
    />
</div>

@php
    $isStudent = auth()->user()->user_type === 'student';

    $options = [
        ['label' => 'Nombre', 'path' => 'name'],
        ['label' => 'Descripción', 'path' => 'description'],
        ['label' => 'Localización', 'path' => 'storage'],
        ['label' => 'Armario', 'path' => 'cabinet'],
        ['label' => 'Balda', 'path' => 'shelf'],
        ['label' => 'Cajón', 'path' => 'drawer'],
        ...(!$isStudent ? [
            ['label' => 'Unidades', 'path' => 'units'],
            ['label' => 'Unidades mínimas', 'path' => 'min_units'],
        ] : [])
    ];

    $columns = [
        'Imagen', 'Nombre', 'Descripción', 'Localización', 'Armario', 'Balda', 'Cajón',
        ...(!$isStudent ? ['Unidades', 'Unidades mínimas'] : [])
    ];
@endphp

<x-search-bar-tool :options="$options" />

<div id="summary-card-view" class="card-grid"></div>

<x-fillable-table
    :wrapper-id="'summary-table-view'"
    :table-id="'summary-table'"
    :should-hide="true"
    :columns="$columns"
/>
<x-pagination
    :option-list="['6', '18', '30', '60']"
/>

@endsection

@push('scripts')
    <script type="application/json" id="summary-data">@json($summary)</script>
    <script type="module" src="{{ asset('js/materials/history/summary.js') }}"></script>
@endpush
