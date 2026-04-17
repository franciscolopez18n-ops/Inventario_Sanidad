@extends('layout.app')

@section('title', 'Actividades')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/activities/createActivity.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
@endpush

@section('content')
    <div class="activity-container">
        <h1>Registrar actividad</h1>

        <div class="">
            <form action="{{ route('activities.store') }}" method="POST">
                @csrf
                <div class="">
                    <label for="title"></label>
                    <textarea name="title" placeholder="Título descriptivo de la actividad..." id="title" rows="4" cols="50" maxlength="100" required></textarea>
                    @error('title')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>

                <div class="">
                    <label for="activity_datetime">Fecha y hora:</label>
                    <input type="datetime-local" id="activity_datetime" name="activity_datetime">
                    @error('activity_datetime')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    <label for="teacher_id">Profesor:</label>

                    <td data-label="Profesor" >
                        <select name="teacher_id" id="teacher_id">
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->user_id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    @error('teacher_id')
                        <div class="alert alert-error alert-form">{{ $message }}</div>
                    @enderror
                </div>

                <h2>Materiales utilizados</h2>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="wide">Material</th>
                                <th>Cantidad</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Material" class="cell-description custom-scroll">
                                    <input list="materials" name="materialName" id="materialName">
                                    <datalist id="materials">
                                        @foreach ($materials as $material)
                                            <option data-id="{{ $material->material_id }}" value="{{ $material->name }}">
                                        @endforeach
                                    </datalist>
                                </td>
                                <td data-label="Cantidad">
                                    <input type="number" name="units" id="units">
                                </td>
                                <td>
                                    <button type="button" name="addButton" id="addButton" class="btn btn-primary">Añadir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <!-- Hidden que contendrá el JSON de la cesta -->
                <input type="hidden" name="materialsBasketInput" id="materialsBasketInput">

                {{-- Alertas flash --}}
                <x-alerts />

                <input type="submit" value="Crear" class="btn btn-primary">
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/activity.js') }}" type="text/javascript"></script>
@endpush

