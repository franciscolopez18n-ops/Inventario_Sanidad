@extends('layout.app')

@section('title', 'Actividades')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/fillable-table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/activities/create.css') }}">
@endpush

@section('content')
    <div class="activity-container">
        <h1>Registrar actividad</h1>

        <form action="{{ route('activities.store') }}" method="POST">
            @csrf
            
            <div class="">
                <label for="title"></label>
                <textarea name="title" placeholder="Título descriptivo de la actividad..." id="title" rows="4" cols="50" maxlength="100" class="@error('title') input-error @enderror">{{ old('title') }}</textarea>
                @error('title')
                    <small class="input-error-msg">{{ $message }}</small>
                @enderror
            </div>

            <div class="">
                <label for="activity_datetime">Fecha y hora:</label>
                <input type="datetime-local" id="activity_datetime" name="activity_datetime" value="{{ old('activity_datetime') }}" class="@error('activity_datetime') input-error @enderror">
                @error('activity_datetime')
                    <small class="input-error-msg">{{ $message }}</small>
                @enderror
            </div>

            <div class="">
                <label for="teacher_id">Profesor:</label>
                <td>
                    <select name="teacher_id" id="teacher_id" class="@error('teacher_id') input-error @enderror">
                        <option value="" disabled {{ old('teacher_id') ? '' : 'selected' }}>Selecciona un profesor...</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->user_id }}" {{ old('teacher_id') == $teacher->user_id ? 'selected' : '' }}>
                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                @error('teacher_id')
                    <small class="input-error-msg">{{ $message }}</small>
                @enderror
            </div>

            <h2>Materiales utilizados</h2>
            <x-fillable-table
                :table-id="'materials-batch-table'"
                :columns="['Material', 'Cantidad']"
                :actions-colspan="1"
            >
                <x-slot name="pinnedRows">
                    <tr>
                        <td class="cell-description">
                            <select name="material" id="material">
                                <option value="" selected disabled>--Selecciona un material--</option>
                                @foreach ($materials->sortBy('name') as $material)
                                    <option value="{{ $material->material_id }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="units" id="units" min="1">
                        </td>
                        <td>
                            <button type="button" id="add-material-btn" class="btn btn-primary">Añadir</button>
                        </td>
                    </tr>
                </x-slot>
            </x-fillable-table>

            <input type="submit" value="Crear" class="btn btn-primary">
        </form>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/activities/create.js') }}" type="text/javascript"></script>
@endpush

