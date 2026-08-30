@extends('layout.app')

@section('title', 'Actualizacion de materiales')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/storages/update.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
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
                <input type="text" id="buscarId" placeholder="Buscar..." autocomplete="off">
                <div class="dropdown-container">
                    <button type="button" id="filterToggle"><i class="fa-solid fa-filter table-icon-interactive"></i></button>
                    <div id="filterOptions" class="filter-options">
                        <label><input type="radio" name="filtro" value="1" checked>Nombre</label>
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
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/storages/load.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/components/loader.js') }}"></script>
    <script src="{{ asset('js/utils/tables.js') }}"></script>
    <script src="{{ asset('js/storages/manageTable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/components/filterToggle.js') }}"></script>
    
@endpush

