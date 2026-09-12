@extends('layout.app')

@section('title', 'Control de usuarios')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/users/users.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/paginated-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/confirm-dialog.css') }}">
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="loader-overlay">
    <div class="spinner"></div>
</div>
<div class="">
    <dialog id="confirmation">
        <p>¿Está seguro de que desea eliminar el usuario seleccionado?</p>
        <input type="button" class="btn btn-success" value="Aceptar" id="acept">
        <input type="button" class="btn btn-danger" value="Cancelar" id="cancel">
    </dialog>
    <dialog id="confirmacionContra">
        <p>¿Está seguro de que desea generar una nueva contraseña?</p>
        <input type="button" class="btn btn-success" value="Aceptar" id="aceptarContra">
        <input type="button" class="btn btn-danger" value="Cancelar" id="cancelarContra">
    </dialog>

    <h1>Gestion de usuarios</h1>

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
                    <label><input type="radio" name="filter" value="5">Fecha de alta</label>
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
@endsection

@push('scripts')
    <script src="{{ asset('js/components/loader.js') }}"></script>
    <script src="{{ asset('js/components/confirmDialog.js') }}"></script>
    <script src="{{ asset('js/users/manage/dataLoad.js') }}"></script>
    <script src="{{ asset('js/components/paginatedTable.js') }}"></script>
    <script type="module" src="{{ asset('js/users/manage/table.js') }}"></script>
    <script src="{{ asset('js/components/filterToggle.js') }}"></script> 
@endpush
