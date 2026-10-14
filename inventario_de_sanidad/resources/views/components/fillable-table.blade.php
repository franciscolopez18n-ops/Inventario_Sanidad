@props([
    'wrapperId' => '',
    'tableId' => '',
    'shouldHide' => false,
    'columns' => [],    // caso simple: una sola fila, array de strings
                        // caso avanzado: varias filas, array de filas donde cada una es un array de entradas
    'numActions' => 0,
])

@php
    $isMultiRow = is_array($columns[0] ?? null);

    if ($isMultiRow) {
        $multiColumnRows = $columns;

        $normalizeHeaderRows = function ($rows) {
            foreach ($rows as &$row) {
                foreach ($row as &$entry) {
                    if (is_string($entry)) {
                        $entry = ['column' => $entry, 'rowspan' => 1, 'colspan' => 1];
                    }

                    $entry = [
                        'column' => $entry['column'] ?? '',
                        'rowspan' => $entry['rowspan'] ?? 1,
                        'colspan' => $entry['colspan'] ?? 1,
                    ];
                }
                unset($entry);
            }
            unset($row);

            return $rows;
        };

        // Una cabecera de varias filas suele usarse para agrupar visualmente columnas relacionadas:
        // una celda ancha (colspan > 1) actúa como título de grupo sobre varias columnas más estrechas
        // que sí corresponden a datos reales. Junto a ellas puede haber columnas individuales que evitan
        // cualquier agrupación mediante rowspan. Sea cual sea su forma, las columnas reales son siempre
        // las que se alinean verticalmente con cada unidad de dato de la tabla, "cortando" las agrupaciones
        // de colspan. Identificarlas es necesario para saber dónde aplicar cada ancho de columna en el <colgroup>.
        // Para ello usamos una rejilla de ocupación: una celda es una columna real si su rowspan
        // llega hasta la última fila de cabecera y su colspan es 1. Las celdas con colspan > 1 que no
        // llegan al final son agrupadores visuales, no columnas
        //
        // Limitación intencional: una celda que llegue hasta el final con colspan > 1 técnicamente no sería
        // un agrupador, sino N columnas fusionadas, pero ese caso no se contempla por ahora

        $computeColumnCount = function ($rows) {
            $grid = [];
            $resolvedColumnCount = 0;
            $numRows = count($rows);

            foreach ($rows as $rowIndex => $row) {
                $colIndex = 0;
                foreach ($row as $cell) {
                    while (!empty($grid[$rowIndex][$colIndex])) {
                        $colIndex++;
                    }
                    $startCol = $colIndex;

                    for ($r = $rowIndex; $r < $rowIndex + $cell['rowspan']; $r++) {
                        for ($c = $startCol; $c < $startCol + $cell['colspan']; $c++) {
                            $grid[$r][$c] = true;
                        }
                    }

                    $reachesBottom = ($rowIndex + $cell['rowspan'] - 1) === $numRows - 1;
                    if ($reachesBottom && $cell['colspan'] === 1) {
                        $resolvedColumnCount++;
                    }

                    $colIndex = $startCol + $cell['colspan'];
                }
            }

            return $resolvedColumnCount;
        };

        // Añade las acciones como última(s) columna(s) de la primera fila, con rowspan obligatorio igual al número de filas de cabecera
        if ($numActions > 0) {
            $multiColumnRows[0] = array_merge($multiColumnRows[0], array_fill(0, $numActions, [
                'column' => '',
                'rowspan' => count($multiColumnRows),
                'colspan' => 1,
            ]));
        }

        $multiColumnRows = $normalizeHeaderRows($multiColumnRows);
        $columnCount = $computeColumnCount($multiColumnRows);
    } else {
        $simpleColumns = $columns;

        if ($numActions > 0) {
            $simpleColumns = array_merge($simpleColumns, array_fill(0, $numActions, ''));
        }

        $columnCount = count($simpleColumns);
    }

@endphp

<div id="{{ $wrapperId }}" class="table-wrapper {{ $shouldHide ? 'hidden' : '' }}">
    <div class="table-sizer">
        <table id="{{ $tableId }}" class="table">
            <colgroup>
                @for ($i = 1; $i <= $columnCount; $i++)
                    <col class="col-{{ $i }}">
                @endfor
            </colgroup>
            <thead>
                @if ($isMultiRow)
                    @foreach ($multiColumnRows as $row)
                        <tr>
                            @foreach ($row as $cell)
                                <th
                                    @if ($cell['rowspan'] > 1) rowspan="{{ $cell['rowspan'] }}" @endif
                                    @if ($cell['colspan'] > 1) colspan="{{ $cell['colspan'] }}" @endif
                                >{{ $cell['column'] }}</th>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        @foreach ($simpleColumns as $column)
                            <th>{{ $column }}</th>
                        @endforeach
                    </tr>
                @endif
            </thead>
            @if (isset($pinnedRows))
                <tbody class="pinned-rows">
                    {{ $pinnedRows }}
                </tbody>
            @endif

            <tbody class="dynamic-rows">
                <!-- Filas se insertarán aquí -->
            </tbody>
        </table>
    </div>
</div>