@extends('layout.app')

@section('title', 'Códigos QR')

@push('styles')    
<link rel="stylesheet" href="{{ asset('css/qrcodes/qrcodes.css') }}">
@endpush

@section('content')
<!-- PARA DESCARGAR UN ZIP CON TODO -->
<div class="qr-zip">
    <a href="{{ route('qrcodes.descargarZip') }}" class="btn btn-primary">
        Descargar todos los QR (ZIP)
    </a>
    <a href="{{ route('qrcodes.print') }}" class="qr-print-btn" target="_blank">
        Imprimir todos
    </a>
</div>

<div class="qr-grid">
    @foreach ($storages as $storage)
        <div class="qr-item">
            <img src="{{ route('qr.show', basename($storage->qr_path)) }}"
                 alt="QR {{ $storage->material->name }}"
                 style="width: 80px; height: 80px;"><br>
            
            <p style="margin: 4px 0 0; font-size: 12px; font-weight: bold;">{{ $storage->material->name }}</p>
            @php
                $count = $storages->where('material_id', $storage->material_id)->count();
            @endphp

            <p style="margin: 2px 0 0; font-size: 11px; color: #666;">
                @if($count === 2)
                    CAE + Odontología
                @elseif($storage->storage === 'CAE')
                    CAE
                @elseif($storage->storage === 'odontology')
                    Odontología
                @endif
            </p>
            <a class="descargar-qr" href="{{ route('qr.show', basename($storage->qr_path)) }}" download>Descargar</a>
       
        </div>
    @endforeach
</div>


@endsection

@push('scripts')
@endpush
