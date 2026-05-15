@extends('layout.app')

@section('title', 'Gestión de material [PRUEBA]')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dialog.css') }}">
@endpush

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="loader-overlay">
    <div class="spinner"></div>
</div> 

<div>
    <!-- Dialogo para confirmar eliminación de material -->

    <dialog  id="confirmacion">
        <p>¿Estás seguro de que deseas eliminar el material seleccionado?</p>
        <input type="button" class="btn btn-success" value="Aceptar" id="aceptar">
        <input type="button" class="btn btn-danger" value="Cancelar" id="cancelar">
    </dialog>

    <div class="content-wrapper">
        <h2>Gestión de material [PRUEBA]</h2>
        <form class="search-form">
            <!-- Buscador -->
            <div class="search-container">
                <input type="text" id="buscarId" placeholder="Buscar..." autocomplete="off">
                <div class="dropdown-container">
                    <button type="button" id="filterToggle"><i class="fa-solid fa-filter"></i></button>
                    <div id="filterOptions" class="filter-options fade-in">
                        <label><input type="radio" name="filtro" value="1" checked>Nombre</label>
                        <label><input type="radio" name="filtro" value="2">Descripción</label>
                        <label><input type="radio" name="filtro" value="3">Imagen</label>
                    </div>
                </div>
            </div>
        </form>

        <div>

            <!-- Tabla de materiales -->
            <div class="table-wrapper">
                <table class="table custom-scroll">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Imagen</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Tabla de materiales se insertará aquí -->
                    </tbody>
                </table>
            </div>
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

        <!-- Alertas flash -->
        <x-alerts />

        <div id="paginacion" class="pagination-controls">
            <!-- Aquí se inyectarán los botones de paginación desde JS -->
        </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/loader.js') }}"></script>
    <script src="{{ asset('js/dialogFunctions.js') }}"></script>
    <script src="{{ asset('js/materialEdit.js') }}"></script>
    <script src="{{ asset('js/tableFunctions.js') }}"></script>
    <script src="{{ asset('js/tableMaterial2.js') }}"></script>
    <script src="{{ asset('js/filterToggle.js') }}"></script> 
@endpush