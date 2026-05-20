<?php

namespace App\Http\Controllers;

use App\Constants\FlashType;
use App\Traits\HasStorageOperations;
use App\Models\StorageUse;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherStorageController extends Controller {
    use HasStorageOperations;

    /**
     * Muestra la vista principal de los almacenamientos.
     * Si es administrador vera los dos almacenamientos de uso y reserva.
     * Si es docente vera el almacenamiento de uso.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function updateView() {
        return view('storages.update');
    }

    /**
     * Muestra la vista del docente para editar el almacenamiento de uso de un material específico.
     * @param \App\Models\Material $material
     * @return mixed|\Illuminate\Contracts\View\View
     */
    public function teacherEditView(Material $material, $currentLocation) {
        return view('storages.teacher.edit')->with('material', $material)->with('currentLocation', $currentLocation);
    }

    /**
     * Resta unidades del almacenamiento de uso de un material.
     *
     * Esta función permite al docente restar manualmente el número de unidades disponibles 
     * en el almacenamiento de uso de un material específico. Se registra la modificación
     * y se valida que no se pueda restar más de lo que existe.
     * 
     * @param \Illuminate\Http\Request $request     Datos del formulario, incluyendo las unidades a restar.
     * @param \App\Models\Material $material        Instancia del material que se desea modificar.
     * @param mixed $currentLocation                Ubicación actual del almacenamiento ('CAE' u 'odontology').
     * @return \Illuminate\Http\RedirectResponse    Redirige de vuelta con mensaje de éxito o error.
     */
    public function subtractToUse(Request $request, Material $material, $currentLocation) {
        // Obtiene el registro del almacenamiento de tipo 'use' para la ubicación actual.
        $useRecord = $material->storageUse()->where('storage', $currentLocation)->first();

        // Si no existe el registro se retorna un mensaje de error.
        if (empty($useRecord)) {
            return back()->with(FlashType::ERROR, 'No se ha encontrado el almacenamiento de uso en esta ubicación.');
        }

        $currentUse = $useRecord->units;

        $validated = $request->validate([
            'subtract_units' => "required|integer|min:1|max:{$currentUse}",
        ], [
            'subtract_units.required' => 'Debes indicar cuántas unidades transferir.',
            'subtract_units.integer'  => 'La cantidad debe ser un número entero.',
            'subtract_units.min'      => 'Debes restar al menos 1 unidad.',
            'subtract_units.max'      => "Solo hay {$currentUse} unidades disponibles en uso.",
        ]);

        try {
            // Se extrae la cantidad validada a restar.
            $modifiedUnits = $validated['subtract_units'];

            // Se ejecuta todo dentro de una transacción para asegurar integridad de datos.
            DB::transaction(function() use ($modifiedUnits, $material, $currentLocation, $useRecord) {
                // Disminuye las unidades del almacenamiento de uso.
                StorageUse::where('material_id', $useRecord->material_id)
                    ->where('storage', $useRecord->storage)
                    ->decrement('units', $modifiedUnits);

                // Registra la modificación con unidades negativas.
                $this->storeEditInModification($useRecord->getAssignment(), -$modifiedUnits);
            });

            // Se comprueba que las unidades actualizadas no sean menores que el mínimo de unidades.
            $this->checkUnits($useRecord);

            // Devuelve una respuesta de éxito al usuario.
            return back()->with(FlashType::SUCCESS, "Se han restado {$modifiedUnits} unidades.");
        } catch (\Exception $e) {
            // Si ocurre algún error durante el proceso, muestra un mensaje de error.
            return back()->with(FlashType::ERROR, 'Error al modificar el almacenamiento: ' . $e->getMessage());
        }
    }

    /**
     * Devuelve todos los almacenamientos en formato JSON.
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateData() {
        $storages = DB::table('materials')
            ->join('storages', 'materials.material_id', '=', 'storages.material_id')
            ->join('storage_use', function ($join) {
                $join->on('storages.material_id', '=', 'storage_use.material_id')
                    ->on('storages.storage', '=', 'storage_use.storage');
            })
            ->select(
                'materials.material_id',
                'materials.name',
                'materials.description',
                'materials.image_path',

                'storages.storage',

                'storage_use.units',
                'storage_use.min_units',
                'storage_use.cabinet',
                'storage_use.shelf',
                'storage_use.drawer'
            )
            ->get();

        return response()->json($storages);
    }
}