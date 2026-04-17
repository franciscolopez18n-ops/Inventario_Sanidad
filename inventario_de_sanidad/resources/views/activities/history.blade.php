@extends('layout.app')

@section('title', 'Historial de actividades')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">    
    <link rel="stylesheet" href="{{ asset('css/activities/activityHistory.css') }}">   
@endpush

@section('content')
<div id="loader-overlay">
    <div class="spinner"></div>
</div> 
    <h1 class="activities-title">Historial de actividades</h1>
    <div class="activities-section">
        <div id="activityCardContainer" class="activity-cards-grid"></div>

        <!-- Paginación -->
        <div id="paginacion" class="pagination-controls">
            <div class="pagination-select">
                <label for="regsPorPagina"></label>
                <select id="regsPorPagina">
                    <option value="5" selected>5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>

            <div class="pagination-buttons">
                <!-- Botones de paginación se insertarán aquí -->
            </div>
        </div>

        {{-- Alertas flash --}}
        <x-alerts />

        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/activitiesHistory.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/loader.js') }}"></script>
    <script src="{{ asset('js/tableFunctions.js') }}"></script>
    <script src="{{ asset('js/tableActivityHistory.js') }}" type="text/javascript"></script>
@endpush