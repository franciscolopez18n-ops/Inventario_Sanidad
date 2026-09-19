@props(['viewBtns'])

<div class="view-toggle">
    @foreach($viewBtns as $i => $btn)
        <button id="{{ $btn['id'] }}" class="btn btn-outline btn-notifications">
            @if($btn['icon'])
                <i class="{{ $btn['icon'] }}"></i>
            @endif
        </button>
    @endforeach
</div>