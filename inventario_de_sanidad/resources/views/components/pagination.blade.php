@props(['optionList' => ['5', '10', '25', '50']])

<!-- Paginación -->
<div class="pagination-controls">
    <div class="pagination-select">
        <label for="rows-per-page"></label>
        <select id="rows-per-page">
            @foreach($optionList as $i => $option)
                <option value="{{ $option }}" {{ $i === 0 ? 'selected' : ''}}>{{ $option }}</option>
            @endforeach
        </select>
    </div>

    <div class="pagination-buttons">
        <!-- Botones de paginación se insertarán aquí -->
    </div>
</div>