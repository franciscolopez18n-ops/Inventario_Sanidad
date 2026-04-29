@extends('layout.app')

@section('title', 'Alta de usuarios')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/users/users.css') }}">
@endpush

@section('content')

<div class="alta-usuarios-container">
    <form action="{{ route('altaUsers.process') }}" method="POST">
        @csrf

        <h1>Alta de usuarios</h1>
        <h4>Introduce los datos de los usuarios que deseas registrar.</h4>

        <div class="input-group">
            <input type="text" id="nombre" name="nombre" placeholder="Nombre" value="{{ old('nombre') }}" class="@error('nombre') is-invalid @enderror">
            @error('nombre') <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
        </div>

        <div class="input-group">
            <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" value="{{ old('apellidos') }}" class="@error('apellidos') is-invalid @enderror">
            @error('apellidos') <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
        </div>

        <div class="input-group">
            <input type="text" id="email" name="email" placeholder="Email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror">
            @error('email') <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
        </div>

        <div class="input-group">
            <select id="user_type" name="user_type" class="@error('user_type') is-invalid @enderror">
                <option value="" disabled {{ old('user_type') ? '' : 'selected' }}>Selecciona un tipo de usuario</option>
                <option value="teacher" {{ old('user_type') == 'teacher' ? 'selected' : '' }}>Docente</option>
                <option value="student" {{ old('user_type') == 'student' ? 'selected' : '' }}>Alumno</option>
                <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
            @error('user_type') <div class="alert alert-error alert-form">{{ $message }}</div> @enderror
        </div>

        {{-- Alertas flash --}}
        <x-alerts />

        <input class="btn btn-primary" type="submit" value="Registrar">
    </form>
</div>

@endsection

