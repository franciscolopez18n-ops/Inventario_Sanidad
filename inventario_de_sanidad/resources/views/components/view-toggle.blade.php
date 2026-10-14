@props(['viewBtns'])

<div class="view-toggle">
    @foreach($viewBtns as $i => $btn)
        <button id="{{ $btn['id'] }}" type="button" class="btn btn-outline {{ $btn['extra_class'] ?? '' }} {{ $i === 0 ? 'active' : '' }}">
            {{ $btn['text'] ?? '' }}

            @if(isset($btn['icon']))
                <i class="{{ $btn['icon'] }}"></i>
            @endif
        </button>
    @endforeach
</div>