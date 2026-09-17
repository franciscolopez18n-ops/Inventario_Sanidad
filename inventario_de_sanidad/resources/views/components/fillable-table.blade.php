@props(['id' => '', 'columns' => [], 'actionsColspan' => 0])

<!-- Tabla -->
<div class="table-wrapper">
    <table id="{{ $id }}" class="table">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
                @if ($actionsColspan > 0)
                    <th colspan="{{ $actionsColspan }}"></th>
                @endif
            </tr>
        </thead>
        <tbody>
            <!-- Filas se insertarán aquí -->
        </tbody>
    </table>
</div>