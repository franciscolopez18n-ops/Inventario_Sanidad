@props([
    'wrapperId' => '',
    'tableId' => '',
    'shouldHide' => false,
    'columns' => [],       // caso simple: array de strings, una sola fila de cabecera
    'headerRows' => [],    // caso avanzado: array de filas; cada fila es un array de entradas
    'actionsColspan' => 0, // solo aplica al caso simple (columns)
])

@php
    // Normaliza una entrada de cabecera: puede ser un string simple o un array
    // ['column' => 'Etiqueta', 'rowspan' => n, 'colspan' => m]
    $normalizeEntry = function ($entry) {
        if (is_string($entry)) {
            return ['column' => $entry, 'rowspan' => 1, 'colspan' => 1];
        }
        return [
            'column' => $entry['column'] ?? '',
            'rowspan' => $entry['rowspan'] ?? 1,
            'colspan' => $entry['colspan'] ?? 1,
        ];
    };

    $useHeaderRows = !empty($headerRows);
@endphp

<div id="{{ $wrapperId }}" class="table-wrapper {{ $shouldHide ? 'hidden' : '' }}">
    <table id="{{ $tableId }}" class="table">
        <thead>
            @if ($useHeaderRows)
                @foreach ($headerRows as $row)
                    <tr>
                        @foreach ($row as $entry)
                            @php $cell = $normalizeEntry($entry); @endphp
                            <th
                                @if ($cell['rowspan'] > 1) rowspan="{{ $cell['rowspan'] }}" @endif
                                @if ($cell['colspan'] > 1) colspan="{{ $cell['colspan'] }}" @endif
                            >{{ $cell['column'] }}</th>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                    @if ($actionsColspan > 0)
                        <th colspan="{{ $actionsColspan }}"></th>
                    @endif
                </tr>
            @endif
        </thead>
        <tbody>
            @if (isset($pinnedRows))
                <tbody class="pinned-rows">
                    {{ $pinnedRows }}
                </tbody>
            @endif

            <tbody class="dynamic-rows">
                <!-- Filas se insertarán aquí -->
            </tbody>
        </tbody>
    </table>
</div>