@props([
    'formGroup' => '',
    'labelText',
    'inputId' => 'image',
    'inputName' => 'image',
    'isClientField' => false,
    'initialStorageRelativePath' => null,
    'initialAlt' => 'Vista previa de la imagen',
])

@php
    $hasServerImage = !empty($initialStorageRelativePath);
@endphp

<div class="{{ $formGroup }} image-upload">
    <label for="{{ $inputId }}" class="btn btn-primary">{{ $labelText }} <i class="fa-solid fa-image"></i></label>
    <input type="file"
       {{ !$inputName ? '' : ($isClientField ? 'data-client-name=' . $inputName : 'name=' . $inputName) }}
       id="{{ $inputId }}"
       accept="image/jpeg,image/png"
       class="image-upload-input">

    <div class="image-preview-group">
        @if($hasServerImage)
            <input type="hidden"
                {{ !$inputName ? '' : ($isClientField ? 'data-client-name=remove_' . $inputName : 'name=remove_' . $inputName) }}
                class="remove-image-flag" value="0">
        @endif

        <div class="image-preview-wrapper {{ $hasServerImage ? '' : 'hidden' }}">
            <img class="image-preview"
                src="{{ $hasServerImage ? asset('storage/' . $initialStorageRelativePath) : '' }}"
                alt="{{ $hasServerImage ? $initialAlt : '' }}">
            <button type="button" class="image-preview-remove">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <span class="file-name-display">{{ $hasServerImage ? '' : 'Ningún archivo seleccionado' }}</span>
    </div>

    @if(!$isClientField && $inputName)
        @error($inputName)
            <small class="input-error-msg">{{ $message }}</small>
        @enderror
    @endif
</div>