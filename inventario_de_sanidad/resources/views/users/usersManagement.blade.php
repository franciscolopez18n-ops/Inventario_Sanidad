@extends('layout.app')

@section('title', 'Control de usuarios')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/users/users.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dialog.css') }}">
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="loader-overlay">
    <div class="spinner"></div>
</div>
<div class="">
    <dialog id="confirmacion">
        <p>¿Está seguro de que desea eliminar el usuario seleccionado?</p>
        <input type="button" class="btn btn-success" value="Aceptar" id="aceptar">
        <input type="button" class="btn btn-danger" value="Cancelar" id="cancelar">
    </dialog>
    <dialog id="confirmacionContra">
        <p>¿Está seguro de que desea generar una nueva contraseña?</p>
        <input type="button" class="btn btn-success" value="Aceptar" id="aceptarContra">
        <input type="button" class="btn btn-danger" value="Cancelar" id="cancelarContra">
    </dialog>

    {{-- Alertas flash --}}
    <x-alerts />

    <h1>Gestion de usuarios</h1>

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
                    <label><input type="radio" name="filtro" value="5">Fecha de alta</label>
                </div>
            </div>
        </div>
    </form>

    <div class="table-wrapper">
        <table id="tabla-usuarios" class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Tipo de usuario</th>
                    <th>Fecha de alta</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody></tbody>
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
@endsection

@push('scripts')
    <script src="{{ asset('js/loader.js') }}"></script>
    <script src="{{ asset('js/dialogFunctions.js') }}"></script>
    <script src="{{ asset('js/usersManagement.js') }}"></script>
    <script src="{{ asset('js/tableFunctions.js') }}"></script>
    <script src="{{ asset('js/tableUser.js') }}"></script>
    <script src="{{ asset('js/filterToggle.js') }}"></script> 
@endpush
