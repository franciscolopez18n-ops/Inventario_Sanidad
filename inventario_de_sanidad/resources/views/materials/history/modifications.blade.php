@extends('layout.app')

@section('title', 'Historial de modificaciones')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/paginated-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
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
            <input type="text" id="search-input" placeholder="Buscar..." autocomplete="off">
            <div class="dropdown-container">
                <button type="button" id="filter-toggle"><i class="fa-solid fa-filter table-icon-interactive"></i></button>
                <div id="filter-options" class="filter-options fade-in">
                    <label><input type="radio" name="filter" value="1" checked>Nombre</label>
                    <label><input type="radio" name="filter" value="2">Apellidos</label>
                    <label><input type="radio" name="filter" value="3">Email</label>
                    <label><input type="radio" name="filter" value="4">Tipo de usuario</label>
                    <label><input type="radio" name="filter" value="5">Material</label>
                    <label><input type="radio" name="filter" value="6">Unidades modificadas</label>
                    <label><input type="radio" name="filter" value="7">Localización</label>
                    <label><input type="radio" name="filter" value="8">Tipo de almacenamiento</label>
                    <label><input type="radio" name="filter" value="9">Fecha de modificación</label>
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
    <script src="{{ asset('js/materials/history/modifications/dataLoad.js') }}"></script>
    <script src="{{ asset('js/components/loader.js') }}"></script>
    <script src="{{ asset('js/components/paginatedTable.js') }}"></script>
    <script type="module" src="{{ asset('js/materials/history/modifications/table.js') }}"></script>
@endpush
