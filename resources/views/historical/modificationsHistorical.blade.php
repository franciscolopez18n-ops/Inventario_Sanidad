@extends('layout.app')

@section('title', 'Historial de modificaciones')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
@endpush

@section('content')

{{-- Overlay para cargar --}}
<div id="loader-overlay">
    <div class="spinner"></div>
</div> 

<div class="history-container">
    <h1>Historial de modificaciones</h1>
    <form class="search-form">
        <!-- Buscador -->
        <div class="search-container">
            <input type="text" id="buscarId" placeholder="Buscar..." autocomplete="off">
            <div class="dropdown-container">
                <button type="button" id="filterToggle"><i class="fa-solid fa-filter"></i></button>
                <div id="filterOptions" class="filter-options fade-in">
                    <label><input type="radio" name="filtro" value="1" checked>Nombre</label>
                    <label><input type="radio" name="filtro" value="2">Apellidos</label>
                    <label><input type="radio" name="filtro" value="3">Email</label>
                    <label><input type="radio" name="filtro" value="4">Tipo de usuario</label>
                    <label><input type="radio" name="filtro" value="5">Material</label>
                    <label><input type="radio" name="filtro" value="6">Unidades modificadas</label>
                    <label><input type="radio" name="filtro" value="7">Localización</label>
                    <label><input type="radio" name="filtro" value="8">Tipo de almacenamiento</label>
                    <label><input type="radio" name="filtro" value="9">Fecha de modificación</label>
                </div>
            </div>
        </div>
    </form>
    
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Tipo de usuario</th>
                    <th>Material</th>
                    <th>Unidades modificadas</th>
                    <th>Localización</th>
                    <th>Tipo de almacenamiento</th>
                    <th>Fecha de modificación</th>
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
    <script src="{{ asset('js/historicalModifications.js') }}"></script>
    <script src="{{ asset('js/loader.js') }}"></script>
    <script src="{{ asset('js/tableFunctions.js') }}"></script>
    <script src="{{ asset('js/tableHistorical.js') }}"></script>
    <script src="{{ asset('js/filterToggle.js') }}"></script> 
@endpush
