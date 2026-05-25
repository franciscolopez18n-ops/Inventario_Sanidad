@extends('layout.app')

@section('title', 'Editar Material')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/materials/materials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
@endpush

@section('content')
<div class="material-form-wrapper">
    <h1>Editar Material</h1>

    <form
        action="{{ route('materials.update.submit', $material->material_id) }}" 
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
            <input type="file" name="image" id="image" class="file-upload-input" onchange="previewImage(event, '#imgPreview')">
            <img id="imgPreview"
                src="{{ asset($material->image_path ? 'storage/' . $material->image_path : 'img/no_image.jpg') }}"
                alt="Previsualización"
                style="max-width: 150px; display: block; margin-top: 10px;">
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
                        <div class="form-grid">
                            <div>
                                <label>Cantidad</label>
                                <input type="number" name="{{ $s }}[use_units]" value="{{ old("$s.use_units", $useRecord->units ?? 0) }}" class="soloLectura @error("$s.use_units") input-error @enderror" readonly>
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
                        <div class="transferir-stock">
                            <div class="tituloTransferir">Transferir unidades</div>
                            <div class="contenedorTransferir">
                                <button type="button" class="transferir-btn down" onclick="moveStock('{{ $s }}', -1)">
                                    <span>A reserva</span>
                                    <small>Uso → Reserva</small>
                                </button>
                                <div class="transfer-center">
                                    <div class="transfer-icon">⇄</div>
                                </div>
                                <button type="button" class="transferir-btn up" onclick="moveStock('{{ $s }}', 1)">
                                    <span>A uso</span>
                                    <small>Uso ← Reserva</small>
                                </button>
                            </div>
                        </div>

                        <!-- RESERVA -->
                        <p><strong>Reserva</strong></p>
                        <div class="form-grid">
                            <label>Nuevas unidades en reserva</label>
                            <div style="display:flex; gap:0.5rem;">
                                <input type="number" name="{{ $s }}[suministro]" min="0" placeholder="Total de unidades"
                                    onkeydown="if(event.key==='Enter'){event.preventDefault();suministrar('{{ $s }}');}">
                                <button type="button" class="btn btn-primary" onclick="suministrar('{{ $s }}')">Establecer</button>
                            </div>
                            <div>
                                <label>Cantidad</label>
                                <input type="number" name="{{ $s }}[reserve_units]" value="{{ old("$s.reserve_units", $reserveRecord->units ?? 0) }}" class="soloLectura @error("$s.reserve_units") input-error @enderror" readonly>
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
            <br><br>
            <a href="{{ route('materials.update.index') }}" class="btn btn-outline">Volver al listado</a><br>
        </div>
        <br>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/previewImage.js') }}"></script>
    <script src="{{ asset('js/transferirStock.js') }}"></script>
@endpush