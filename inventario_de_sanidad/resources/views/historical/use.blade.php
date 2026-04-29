@extends('layout.app')

@section('title', 'Materiales en uso')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/historical/historical.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
    
@endpush

@section('content')

<div id="loader-overlay">
    <div class="spinner"></div>
</div> 
<div class="historical-container">
    <h1>Materiales en Uso</h1>
    <form class="search-form">
        <!-- Buscador -->
        <div class="search-container">
            <input type="text" id="buscarId" placeholder="Buscar..." autocomplete="off">
            <div class="dropdown-container">
                <button type="button" id="filterToggle"><i class="fa-solid fa-filter"></i></button>
                <div id="filterOptions" class="filter-options">
                    <label><input type="radio" name="filtro" value="1" checked>Nombre</label>
                    <label><input type="radio" name="filtro" value="2">Descripción</label>
                    <label><input type="radio" name="filtro" value="3">Localización</label>
                    <label><input type="radio" name="filtro" value="4">Armario</label>
                    <label><input type="radio" name="filtro" value="5">Balda</label>
                    @if(Cookie::get('TYPE') != 'student')
                        <label><input type="radio" name="filtro" value="6">Unidades</label>
                        <label><input type="radio" name="filtro" value="7">Unidades Mínimas</label>
                    @endif

                </div>
            </div>
        </div>
    </form>
        @if(Cookie::get('TYPE') != 'student')
            <div class="view-toggle">
                <button id="cardViewBtn" class="btn btn-outline btn-notifications active"><i class="fa-solid fa-list-ul"></i> </button>
                <button id="tableViewBtn" class="btn btn-outline btn-notifications"><i class="fa-solid fa-table"></i> </button>
            </div>
        @endif
    </div>
    
<div id="cardView"  class="card-grid"></div>
       

        <div id="tableView" class="table-wrapper" style="display: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Localización</th>
                        <th>Armario</th>
                        <th>Balda</th>
                        <th>Unidades</th>
                        <th>Unidades Mínimas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        /* @foreach($materials as $material)
                            <tr>
                                <td>
                                    <img src="{{ asset($material->image_path 
                                        ? 'storage/' . $material->image_path 
                                        : 'img/no_image.jpg') 
                                    }}" alt="{{ $material->name }}" class="cell-img">
                                </td>
                                <td>{{ $material->name }}</td>
                                <td class="cell-description">{{ $material->description }}</td>
                                <td>{{ $material->cabinet }}</td>
                                <td>{{ $material->shelf }}</td>
                                <td>{{ $material->units }}</td>
                                <td>{{ $material->min_units }}</td>
                            </tr>
                        @endforeach */
                    ?>
                </tbody>
            </table>
        </div>
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
@endsection

@push('scripts')
    <script src="{{ asset('js/historicalFunctions.js') }}"></script>
    <script src="{{ asset('js/loader.js') }}"></script>
    <script src="{{ asset('js/tableFunctions.js') }}"></script>
    <script src="{{ asset('js/tableReserveUse.js') }}"></script>
    <script src="{{ asset('js/filterToggle.js') }}"></script> 
@endpush
