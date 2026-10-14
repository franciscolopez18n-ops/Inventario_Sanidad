@extends('layout.app')

@section('title', 'Bienvenido')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/welcome/welcome.css') }}">
@endpush

@section('content')

<!-- Dialog para cambiar contraseña -->
<dialog id="first-log-dialog">
    <div class="modal-content">
        <h2>Cambiar Contraseña</h2>
        <p>En el primer ingreso a la página se ha de cambiar la contraseña.</p>
        <form action="{{ route('welcome.change-password') }}" method="POST">
            @csrf

            <input type="password" name="new_password" placeholder="Nueva contraseña" class="@error('new_password') input-error @enderror">
            @error('new_password') <small class="input-error-msg">{{ $message }}</small> @enderror
            <input type="password" name="confirm_password" placeholder="Confirma la nueva contraseña" class="@error('confirm_password') input-error @enderror">
            @error('confirm_password') <small class="input-error-msg">{{ $message }}</small> @enderror

            <button class="btn btn-primary" type="submit">Cambiar Contraseña</button>
        </form>
    </div>
</dialog>

<div id="watermark-text" aria-hidden="true">Portal de Sanidad</div>
<img id="watermark-img" src="{{ asset('img/logo.png') }}" alt="" aria-hidden="true">

@endsection

@push('scripts')
    <script type="application/json" id="user-data">@json(auth()->user())</script>
    <script src="{{ asset('js/welcome/firstLogin.js') }}"></script>
@endpush