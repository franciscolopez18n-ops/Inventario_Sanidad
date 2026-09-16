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