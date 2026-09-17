@extends('layout.app')

@section('title', 'Historial de actividades')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/paginated-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">    
    <link rel="stylesheet" href="{{ asset('css/activities/history.css') }}">   
@endpush

@section('content')

<x-loader />

<h1 class="activities-title">Historial de actividades</h1>

<x-search-bar
    :options="[
        ['label' => 'Fecha y hora', 'path' => 'created_at'],
        ['label' => 'Título', 'path' => 'title'],
        ['label' => auth()->user()->user_type === 'teacher' ? 'Alumno/a' : 'Profesor/a', 'path' => 'partner.full_name'],
        ['label' => 'Material', 'path' => 'materials.name'],
    ]"
/>

<div id="activities-card-container" class="activity-cards-grid"></div>
<x-pagination />

@endsection

@push('scripts')
    <script type="application/json" id="activities-data">@json($activities)</script>
    <script type="module" src="{{ asset('js/activities/history.js') }}" type="text/javascript"></script>
@endpush