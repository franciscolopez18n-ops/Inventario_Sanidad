@extends('layout.app')

@section('title', 'Actualizacion de materiales')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/fillable-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/storages/manage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
@endpush

@section('content')
<div id="loader-overlay"> 
    <div class="spinner"></div>
</div>
<div class="">
    <div class="content-wrapper">
        <h1>Gestionar Almacenamiento</h1>
        
        <!-- Buscador -->
        <form class="search-form">
            <div class="search-container">
                <input type="text" id="search-input" placeholder="Buscar..." autocomplete="off">
                <div class="dropdown-container">
                    <button type="button" id="filter-toggle"><i class="fa-solid fa-filter table-icon-interactive"></i></button>
                    <div id="filter-options" class="filter-options">
                        <label><input type="radio" name="filter" value="1" checked>Nombre</label>
                    </div>
                </div>
            </div>
        </form>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Localización</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Cantidad mínima</th>
                    <th>Armario</th>
                    <th>Balda</th>
                    <th>Cajón</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
        <!-- Paginación -->
        <div id="paginacion" class="pagination-controls">
            <div class="pagination-select">
                <label for="rows-per-page"></label>
                <select id="rows-per-page">
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
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/storages/manage/dataLoad.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/components/paginatedTable.js') }}"></script>
    <script type="module" src="{{ asset('js/storages/manage/table.js') }}" type="text/javascript"></script>
@endpush

