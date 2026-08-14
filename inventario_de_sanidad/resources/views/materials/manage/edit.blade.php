@extends('layout.app')

@section('title', 'Editar Material')

@push('styles')    
    <link rel="stylesheet" href="{{ asset('css/materials/materials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/materials/edit.css') }}">
@endpush

@section('content')
<div class="material-form-wrapper">
    <h1>Editar Material</h1>

    <form
        action="{{ route('materials.manage.update', $material->material_id) }}" 
        method="POST"
        enctype="multipart/form-data" 
        class="material-form"
    >
        @csrf

        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name', $material->name) }}" class="@error('name') input-error @enderror">
            @error('name')
                <small class="input-error-msg">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea name="description" id="description" rows="3" class="@error('description') input-error @enderror">{{ old('description', $material->description) }}</textarea>
            @error('description')
                <small class="input-error-msg">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group file-upload">
            <label for="image" class="btn btn-primary">Cambiar Imagen <i class="fa-solid fa-image"></i></label>
            <input type="file" name="image" id="image" class="file-upload-input" onchange="previewImage(event, '#img-preview')">
            <img id="img-preview"
                src="{{ asset($material->image_path ? 'storage/' . $material->image_path : 'img/no_image.jpg') }}"
                alt="Previsualización">
            @error('image')
                <small class="input-error-msg">{{ $message }}</small>
            @enderror
        </div>

        <div class="storage-grid">
            @foreach ($storages as $storage)
                <fieldset>
                    <legend>{{ $storage->storage === 'CAE' ? 'CAE' : 'Odontología' }}</legend>

                    @php
                        $useRecord = $storage->storageUse;
                        $reserveRecord = $storage->storageReserve;
                        $s = $storage->storage;
                    @endphp

                    <div class="storage-block">
                        <!-- USO -->
                        <p><strong>Uso</strong></p>
                        <div class="form-fields">
                            <div>
                                <label>Cantidad</label>
                                <input type="number" name="{{ $s }}[use_units]" value="{{ old("$s.use_units", $useRecord->units ?? 0) }}" class="@error("$s.use_units") input-error @enderror" readonly>
                                @error("$s.use_units") <small class="input-error-msg">{{ $message }}</small> @enderror
                            </div>
                            <div>
                                <label>Cantidad Mínima</label>
                                <input type="number" name="{{ $s }}[use_min_units]" value="{{ old("$s.use_min_units", $useRecord->min_units ?? 0) }}" class="@error("$s.use_min_units") input-error @enderror">
                                @error("$s.use_min_units") <small class="input-error-msg">{{ $message }}</small> @enderror
                            </div>
                            <div>
                                <label>Armario</label>
                                <input type="number" name="{{ $s }}[use_cabinet]" value="{{ old("$s.use_cabinet", $useRecord->cabinet ?? 0) }}" class="@error("$s.use_cabinet") input-error @enderror">
                                @error("$s.use_cabinet") <small class="input-error-msg">{{ $message }}</small> @enderror
                            </div>
                            <div>
                                <label>Balda</label>
                                <input type="number" name="{{ $s }}[use_shelf]" value="{{ old("$s.use_shelf", $useRecord->shelf ?? 0) }}" class="@error("$s.use_shelf") input-error @enderror">
                                @error("$s.use_shelf") <small class="input-error-msg">{{ $message }}</small> @enderror
                            </div>
                            <div>
                                <label>Cajón</label>
                                <input type="number" name="{{ $s }}[use_drawer]" value="{{ old("$s.use_drawer", $useRecord->drawer ?? 0) }}" class="@error("$s.use_drawer") input-error @enderror">
                                @error("$s.use_drawer") <small class="input-error-msg">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <!-- BOTONES PARA TRANSFERIR -->
                        <div class="stock-transfer">
                            <div class="transfer-title">Transferir unidades</div>
                            <div class="transfer-container">
                                <button
                                    type="button"
                                    class="transfer-btn to-reserve"
                                    data-storage="{{ $s }}"
                                    data-direction="to-reserve"
                                    {{ $useRecord->units <= 0 ? 'disabled' : '' }}
                                >
                                    <span>A reserva</span>
                                    <small>Uso → Reserva</small>
                                </button>

                                <i class="fa-solid fa-arrow-right-arrow-left transfer-icon"></i>
                                
                                <button
                                    type="button"
                                    class="transfer-btn to-use"
                                    data-storage="{{ $s }}"
                                    data-direction="to-use"
                                    {{ $reserveRecord->units <= 0 ? 'disabled' : '' }}
                                >
                                    <span>A uso</span>
                                    <small>Uso ← Reserva</small>
                                </button>
                            </div>
                        </div>

                        <!-- RESERVA -->
                        <p><strong>Reserva</strong></p>
                        <div class="form-fields">
                            <label>Total de unidades en reserva</label>
                            <div class="supply-container">
                                <input type="number" id="{{ $s }}_supply" min="0" placeholder="Nuevo total de unidades">
                                <button
                                    type="button"
                                    class="btn btn-primary supply-btn"
                                    data-storage="{{ $s }}"
                                >Establecer</button>
                            </div>
                            <div>
                                <label>Cantidad</label>
                                <input type="number" name="{{ $s }}[reserve_units]" value="{{ old("$s.reserve_units", $reserveRecord->units ?? 0) }}" class="@error("$s.reserve_units") input-error @enderror" readonly>
                                @error("$s.reserve_units") <small class="input-error-msg">{{ $message }}</small> @enderror
                            </div>
                            <div>
                                <label>Cantidad Mínima</label>
                                <input type="number" name="{{ $s }}[reserve_min_units]" value="{{ old("$s.reserve_min_units", $reserveRecord->min_units ?? 0) }}" class="@error("$s.reserve_min_units") input-error @enderror">
                                @error("$s.reserve_min_units") <small class="input-error-msg">{{ $message }}</small> @enderror
                            </div>
                            <div>
                                <label>Armario</label>
                                <input type="text" name="{{ $s }}[reserve_cabinet]" value="{{ old("$s.reserve_cabinet", $reserveRecord->cabinet ?? '') }}" class="@error("$s.reserve_cabinet") input-error @enderror">
                                @error("$s.reserve_cabinet") <small class="input-error-msg">{{ $message }}</small> @enderror
                            </div>
                            <div>
                                <label>Balda</label>
                                <input type="number" name="{{ $s }}[reserve_shelf]" value="{{ old("$s.reserve_shelf", $reserveRecord->shelf ?? 0) }}" class="@error("$s.reserve_shelf") input-error @enderror">
                                @error("$s.reserve_shelf") <small class="input-error-msg">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </fieldset>
            @endforeach
        </div>

        <div class="form-actions">
            <input type="submit" value="Actualizar" class="btn btn-success">
            <a href="{{ route('materials.manage.index') }}" class="btn btn-outline">Volver al listado</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/materials/previewImage.js') }}"></script>
    <script src="{{ asset('js/materials/edit.js') }}"></script>
@endpush