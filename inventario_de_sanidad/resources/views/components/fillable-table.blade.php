@props(['wrapperId' => '', 'tableId' => '', 'shouldHide' => false, 'columns' => [], 'actionsColspan' => 0])

<!-- Tabla -->
<div id="{{ $wrapperId }}" class="table-wrapper {{ $shouldHide ? 'hidden' : '' }}">
    <table id="{{ $tableId }}" class="table">
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