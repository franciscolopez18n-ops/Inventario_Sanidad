@extends('layout.app')

@section('title', 'Historial de modificaciones')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/paginated-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
@endpush

@section('content')

<x-loader />

<h1>Historial de modificaciones</h1>

<x-search-bar
    :options="[
        ['label' => 'Nombre', 'path' => 'first_name'],
        ['label' => 'Apellidos', 'path' => 'last_name'],
        ['label' => 'Email', 'path' => 'email'],
        ['label' => 'Tipo de usuario', 'path' => 'user_type'],
        ['label' => 'Material', 'path' => 'material_name'],
        ['label' => 'Unidades modificadas', 'path' => 'units'],
        ['label' => 'Localización', 'path' => 'storage'],
        ['label' => 'Tipo de almacenamiento', 'path' => 'storage_type'],
        ['label' => 'Fecha de modificación', 'path' => 'action_datetime'],
    ]"
/>
<x-fillable-table
    :id="'modifications-history-table'"
    :columns="[
        'Nombre',
        'Apellidos',
        'Email',
        'Tipo de usuario',
        'Material',
        'Unidades modificadas',
        'Localización',
        'Tipo de almacenamiento',
        'Fecha de modificación',
    ]"
/>
<x-pagination />
    
@endsection

@push('scripts')
    <script type="application/json" id="modifications-data">@json($modifications)</script>
    <script type="module" src="{{ asset('js/materials/history/modifications.js') }}"></script>
@endpush
