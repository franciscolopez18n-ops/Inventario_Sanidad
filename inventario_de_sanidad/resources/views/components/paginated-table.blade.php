@props(['columns' => [], 'actionsColspan' => 1])

<!-- Tabla -->
<div class="table-wrapper">
    <table class="table custom-scroll">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
                <th colspan="{{ $actionsColspan }}"></th>
            </tr>
        </thead>
        <tbody>
            <!-- Filas se insertarán aquí -->
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="pagination-controls">
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