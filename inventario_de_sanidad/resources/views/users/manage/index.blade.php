@extends('layout.app')

@section('head-extra')
    <x-csrf-meta />
@endsection

@section('title', 'Gestión de usuarios')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/users/users.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/paginated-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/loader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/confirm-dialog.css') }}">
@endpush

@section('content')

<x-loader />

<x-confirm-dialog
    :id="'delete-cd'"
    :message="'¿Estás seguro de que deseas eliminar el usuario seleccionado?'"
/>
<x-confirm-dialog
    :id="'change-password-cd'"
    :message="'¿Estás seguro de que deseas generar una nueva contraseña?'"
/>

<h1>Gestión de usuarios</h1>

<x-search-bar
    :options="[
        ['label' => 'Nombre', 'path' => 'first_name'],
        ['label' => 'Apellidos', 'path' => 'last_name'],
        ['label' => 'Email', 'path' => 'email'],
        ['label' => 'Tipo de usuario', 'path' => 'user_type'],
        ['label' => 'Fecha de alta', 'path' => 'created_at'],
    ]"
/>
<x-fillable-table
    :id="'users-management-table'"
    :columns="['Nombre', 'Apellidos', 'Email', 'Tipo de usuario', 'Fecha de alta']"
    :actions-colspan="2"
/>
<x-pagination />

@endsection

@push('scripts')
    <script type="application/json" id="users-data">@json($users)</script>
    <script type="module" src="{{ asset('js/users/manage/table.js') }}"></script>
@endpush
