@extends('layout.app')

@section('title', 'Materiales en uso')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/paginated-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/materials/history/summary.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
@endpush

@section('content')

<x-loader />

<div class="summary-header">
    <h1>Materiales en Uso</h1>

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
    
    <x-search-bar :options="$options" />

    <x-view-toggle 
        :view-btns="[
            ['id' => 'card-view-btn', 'icon' => 'fa-solid fa-list-ul'],
            ['id' => 'table-view-btn', 'icon' => 'fa-solid fa-table'],
        ]"
    />
</div>

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
