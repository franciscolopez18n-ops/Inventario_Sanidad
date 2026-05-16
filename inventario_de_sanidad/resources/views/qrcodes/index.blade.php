@extends('layout.app')

@section('title', 'Códigos QR')

@push('styles')    
@endpush

@section('content')

<div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0; border-left: 1px solid #ccc; border-top: 1px solid #ccc;">
    @foreach ($storages as $storage)
        <div style="padding: 12px; text-align: center; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;">
            <img src="{{ route('qr.show', basename($storage->qr_path)) }}"
                 alt="QR {{ $storage->material->name }}"
                 style="width: 80px; height: 80px;">
            <p style="margin: 4px 0 0; font-size: 12px; font-weight: bold;">{{ $storage->material->name }}</p>
            <p style="margin: 2px 0 0; font-size: 11px; color: #666;">{{ $storage->storage === 'CAE' ? 'CAE' : 'Odontología' }}</p>
        </div>
    @endforeach
</div>

@endsection

@push('scripts')
@endpush
