@props(['options' => []])

<form class="search-form">
    <!-- Buscador -->
    <div class="search-container">
        <input type="text" id="search-input" placeholder="Buscar..." autocomplete="off">
        <div class="dropdown-container">
            <button type="button" id="filter-toggle"><i class="fa-solid fa-filter table-icon-interactive"></i></button>
            <div id="filter-options" class="filter-options fade-in">
                @foreach ($options as $index => $option)
                    <label>
                        <input
                            type="radio"
                            name="filter"
                            value="{{ $option['field'] }}"
                            {{ $index === 0 ? 'checked' : '' }}
                        >
                        {{ $option['label'] }}
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</form>