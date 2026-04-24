<?php

namespace App\Http\Controllers;

use App\Models\Modification;
use App\Constants\FlashType;
use App\Models\Storage;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\LowStockAlert;
use Illuminate\Support\Facades\Mail;

class StorageController extends Controller
{
    /**
     * Muestra la vista principal de los almacenamientos.
     * Si es administrador vera los dos almacenamientos de uso y reserva.
     * Si es docente vera el almacenamiento de uso.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function updateView()
    {
        return view('storages.update');
    }

    /**
     * Muestra la vista del administrador para editar los almacenamientos de un material específico.
     * @param \App\Models\Material $material
     * @return mixed|\Illuminate\Contracts\View\View
     */
    public function editView(Material $material, $currentLocation)
    {
        return view('storages.edit')->with('material', $material)->with('currentLocation', $currentLocation);
    }

    /**
     * Muestra la vista del docente para editar el almacenamiento de uso de un material específico.
     * @param \App\Models\Material $material
     * @return mixed|\Illuminate\Contracts\View\View
     */
    public function teacherEditView(Material $material, $currentLocation)
    {
        return view('storages.teacher.edit')->with('material', $material)->with('currentLocation', $currentLocation);
    }

    /**
     * Actualiza en batch el almacenamiento de un material en una ubicación específica.
     * Permite modificar unidades, mínimos y ubicaciones (armario, estantería, cajón) 
     * para los tipos de almacenamiento 'use' y 'reserve', con opción de modificar sólo reserva.
     *
     * @param \Illuminate\Http\Request $request        Petición HTTP con los datos de actualización.
     * @param \App\Models\Material    $material        Instancia del Material.
     * @param mixed                   $currentLocation Ubicación actual ('CAE' u 'odontology').
     * @return \Illuminate\Http\RedirectResponse       Redirección con mensaje de resultado.
     */
    public function updateBatch(Request $request, Material $material, $currentLocation)
    {
        $validated = $request->validate([
            'use_units'         => 'required|integer|min:0',
            'use_min_units'     => 'required|integer|min:0',
            'use_cabinet'       => 'required|integer|min:0',
            'use_shelf'         => 'required|integer|min:0',
            'drawer'            => 'required|integer|min:0',

            'reserve_units'     => 'required|integer|min:0',
            'reserve_min_units' => 'required|integer|min:0',
            'reserve_cabinet'   => 'required|string',
            'reserve_shelf'     => 'required|integer|min:0',

            'onlyReserve'       => 'nullable|boolean'
        ]);

        // Registro actuales.
        $useRecord = $material->storage->where('storage_type', 'use')->where('storage', $currentLocation)->first();
        $reserveRecord = $material->storage->where('storage_type', 'reserve')->where('storage', $currentLocation)->first();

        // Si no se encuentra el registro en 'use' o 'reserve' se retorna un error.
        if (empty($reserveRecord)) {
            return back()->with(FlashType::ERROR, 'El material no está añadido en el almacenamiento de reserva.');
        } else if (empty($useRecord)  && !$validated["onlyReserve"]) {
            return back()->with(FlashType::ERROR, 'El material no está añadido en el almacenamiento de uso.');
        }

        // Se asignan los nuevos valores provenientes del request para el almacenamiento de uso.
        $newUseUnits        = $validated['use_units'];
        $newUseMin          = $validated['use_min_units'];
        $newUseCabinet      = $validated['use_cabinet'];
        $newUseShelf        = $validated['use_shelf'];
        $newUseDrawer       = $validated['drawer'];

        // Se asignan los nuevos valores para el almacenamiento de reserva.
        $newReserveUnits    = $validated['reserve_units'];
        $newReserveMin      = $validated['reserve_min_units'];
        $newReserveCabinet  = $validated['reserve_cabinet'];
        $newReserveShelf    = $validated['reserve_shelf'];

        // Se comprueba si los nuevos valores coinciden exactamente con los actuales.
        if
        (
            $newUseUnits        == $useRecord->units && 
            $newUseMin          == $useRecord->min_units && 
            $newUseCabinet      == $useRecord->cabinet && 
            $newUseShelf        == $useRecord->shelf && 
            $newUseDrawer       == $useRecord->drawer &&
            $newReserveUnits    == $reserveRecord->units && 
            $newReserveMin      == $reserveRecord->min_units && 
            $newReserveCabinet  == $reserveRecord->cabinet && 
            $newReserveShelf    == $reserveRecord->shelf
        )
        {
            // Si no se detecta ningún cambio, se retorna un mensaje informativo.
            return back()->with(FlashType::INFO, 'No se han detectado cambios en los datos.');
        }

        try {
            // Solamente actualizar reserva.
            if ($request->boolean('onlyReserve')) {
                // Se calcula la diferencia de unidades para "reserve".
                $differenceReserve = $newReserveUnits - $reserveRecord->units;

                // Se verifica que la diferencia no provoque que el stock de reserva sea negativo.
                if (abs($differenceReserve) > $reserveRecord->units && $reserveRecord->units > 0) {
                    return back()->with(FlashType::ERROR, 'La cantidad de reserva no puede ser negativa.');
                }

                // Inicia una transacción de base de datos: si algo falla, se revierte todo.
                DB::transaction(function() use ($validated, $newReserveUnits, $differenceReserve, $material, $currentLocation) {
                    // Actualiza el almacenamiento de reserva.
                    Storage::where('material_id', $material->material_id)
                    ->where('storage_type' , 'reserve')
                    ->where('storage', $currentLocation)
                    ->update([
                        'units'     => $newReserveUnits,
                        'min_units' => $validated['reserve_min_units'],
                        'cabinet'   => $validated['reserve_cabinet'],
                        'shelf'     => $validated['reserve_shelf'],
                    ]);

                    // Registra la modificación realizada en el almacenamiento de reserva, almacenando la diferencia calculada.
                    $this->storeEditInModification($material->material_id, 'reserve', $differenceReserve, $currentLocation);
                });

                // Se comprueba que las unidades actualizadas no sean menores que el mínimo de unidades.
                $this->comprobateUnits($material, 'reserve', $currentLocation);

                // Devuelve una respuesta de éxito al usuario.
                return back()->with(FlashType::SUCCESS, 'Se ha actualizado correctamente el almacenamiento de reserva.');
            }
    
            // Se calculan las diferencias en unidades para el almacenamiento de uso y reserva.
            $differenceUse     = $newUseUnits     - $useRecord->units;
            $differenceReserve = $newReserveUnits - $reserveRecord->units;
    
            // Si cambia ambas unidades, no se realiza el cambio.
            if ($differenceUse !== 0 && $differenceReserve !== 0) {
                return back()->with(FlashType::ERROR, 'Solo puedes modificar una de las dos cantidades; el otro valor se ajustará automáticamente.');
            }
    
            if ($differenceUse !== 0) {
                // Si se modifica la cantidad de uso, se ajusta automáticamente la de reserva.
                $newReserveUnits  = $reserveRecord->units - $differenceUse;
                if ($newReserveUnits < 0) {
                    return back()->with(FlashType::ERROR, 'No puedes transferir más unidades de las que hay en reserva.');
                }
            } else if ($differenceReserve !== 0) {
                // Si se modifica la cantidad de reserva, se ajusta automáticamente la de uso.
                $newUseUnits  = $useRecord->units - $differenceReserve;
                if ($newUseUnits < 0) {
                    return back()->with(FlashType::ERROR, 'No puedes transferir más unidades de las que hay en uso.');
                }
            }
    
            // Se inicia una transacción para actualizar ambos registros ("use" y "reserve")
            // junto con el registro de las modificaciones de manera atómica.
            DB::transaction(function() use ($validated, $newUseUnits, $newReserveUnits, $differenceUse, $differenceReserve, $material, $currentLocation) {
                // Actualiza el almacenamiento de uso.
                Storage::where('material_id', $material->material_id)
                ->where('storage_type' , 'use')
                ->where('storage', $currentLocation)
                ->update([
                    'units'     => $newUseUnits,
                    'min_units' => $validated['use_min_units'],
                    'cabinet'      => $validated['use_cabinet'],
                    'shelf'        => $validated['use_shelf'],
                    'drawer'        =>  $validated['drawer']
                ]);
    
                // Actualiza el almacenamiento de reserva.
                Storage::where('material_id', $material->material_id)
                ->where('storage_type' , 'reserve')
                ->where('storage', $currentLocation)
                ->update([
                    'units'     => $newReserveUnits,
                    'min_units' => $validated['reserve_min_units'],
                    'cabinet'      => $validated['reserve_cabinet'],
                    'shelf'        => $validated['reserve_shelf'],
                ]);
        
                // Registra las modificaciones correspondientes según cuál de las cantidades haya cambiado.
                if ($differenceUse !== 0) {
                    // Si se modificó la cantidad de uso, se registra la diferencia positiva en "use"
                    $this->storeEditInModification($material->material_id, 'use', $differenceUse,$currentLocation);
                    // y la diferencia negativa en "reserve" para mantener el balance.
                    $this->storeEditInModification($material->material_id, 'reserve', -$differenceUse, $currentLocation);
                } else if ($differenceReserve !== 0) {
                    // Si se modificó la cantidad de reserva, se registra la diferencia positiva en "reserve"
                    $this->storeEditInModification($material->material_id, 'reserve', $differenceReserve, $currentLocation);
                    // y la diferencia negativa en "use" para mantener el balance.
                    $this->storeEditInModification($material->material_id, 'use', -$differenceReserve, $currentLocation);
                }
            });

            // Se comprueba que las unidades actualizadas no sean menores que el mínimo de unidades.
            $this->comprobateUnits($material, 'use', $currentLocation);
            $this->comprobateUnits($material, 'reserve', $currentLocation);
    
            // Devuelve una respuesta de éxito al usuario.
            return back()->with(FlashType::SUCCESS, 'Almacenamiento actualizado correctamente.');
        } catch (\Exception $e) {
            // Si ocurre algún error durante el proceso, muestra un mensaje de error.
            return back()->with(FlashType::ERROR, 'Error al modificar los registros: ' . $e->getMessage());
        }
    }

    /**
     * Registra las unidades modificadas de un material y almacenamiento específico.
     * 
     * Esta función crea un nuevo registro en la tabla de modificaciones (`modifications`)
     * para dejar constancia de un cambio en las unidades de un material en un tipo 
     * específico de almacenamiento (uso o reserva), junto con la ubicación y el usuario.
     * 
     * @param mixed $material_id        ID del material que ha sido modificado.
     * @param mixed $storage_type       Tipo de almacenamiento afectado ('use' o 'reserve').
     * @param mixed $units              Cantidad de unidades modificadas (puede ser positiva o negativa).
     * @param mixed $currentLocation    Identificador del almacenamiento físico ('CAE' u 'odontology').
     * @return void
     */
    private function storeEditInModification($material_id, $storage_type, $units, $currentLocation)
    {
        // Obtiene el ID del usuario desde la cookie 'USERPASS'.
        $user_id = Cookie::get('USERPASS');

        // Crea un nuevo registro en la tabla 'modifications' con los datos de la modificación.
        Modification::create([
            'user_id'         => $user_id,
            'material_id'     => $material_id,
            'storage_type'    => $storage_type,
            'storage'         => $currentLocation,
            'units'           => $units,
            'action_datetime' => Carbon::now('Europe/Madrid'),
        ]);
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
    public function subtractToUse(Request $request, Material $material, $currentLocation)
    {

        // Obtiene el registro del almacenamiento de tipo 'use' para la ubicación actual.
        $useRecord  = $material->storage->where('storage_type', 'use')->where('storage', $currentLocation)->first();

        // Si no existe el registro se retorna un mensaje de error.
        if (!$useRecord) {
            return back()->with(FlashType::ERROR, 'No se ha encontrado el almacenamiento de uso en esta ubicación.');
        }

        // Obtiene las unidades actuales disponibles en uso.
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
            DB::transaction(function() use ($modifiedUnits, $material, $currentLocation) {
                // Disminuye las unidades del almacenamiento de uso.
                Storage::where('material_id', $material->material_id)
                ->where('storage_type','use')
                ->where('storage', $currentLocation)
                ->decrement('units', $modifiedUnits);

                // Registra la modificación con unidades negativas.
                $this->storeEditInModification($material->material_id, 'use', -$modifiedUnits, $currentLocation);
            });

            // Se comprueba que las unidades actualizadas no sean menores que el mínimo de unidades.
            $this->comprobateUnits($material, 'use', $currentLocation);

            // Devuelve una respuesta de éxito al usuario.
            return back()->with(FlashType::SUCCESS, "Se han restado {$modifiedUnits} unidades.");
        } catch (\Exception $e) {
            // Si ocurre algún error durante el proceso, muestra un mensaje de error.
            return back()->with(FlashType::ERROR, 'Error al modificar el almacenamiento: ' . $e->getMessage());
        }
    }

    /**
     * Comprueba las unidades de un material en un tipo de almacenamiento.
     *
     * Si las unidades disponibles son menores que el mínimo definido, 
     * se envía una advertencia por correo electrónico al administrador.
     *
     * @param mixed $material           Instancia del material.
     * @param mixed $storage_type       Tipo de almacenamiento a comprobar ('use' o 'reserve').
     * @param mixed $currentLocation    Ubicación específica ('CAE' u 'odontology').
     * @return void
     */
    private function comprobateUnits(Material $material, $storage_type, $currentLocation)
    {
        // Obtiene solo los correos electrónicos de los usuarios administradores.
        $adminEmails = User::where('user_type', 'admin')
        ->pluck('email') // Devuelve una colección de strings (emails).
        ->toArray();     // Convierte la colección a un array plano.

        // Busca el registro de almacenamiento según el material, tipo de almacenamiento y ubicación.
        $typeRecord = Storage::where('material_id', $material->material_id)
        ->where('storage_type', $storage_type)
        ->where('storage', $currentLocation)
        ->first();

        // Si las unidades disponibles son menores que las mínimas definidas...
        if (!empty($typeRecord) && $typeRecord->units < $typeRecord->min_units) {
            // ...envía un correo de alerta a todos los administradores
            Mail::to($adminEmails)->send(new LowStockAlert($typeRecord, $material->name));
        }
    }

    /**
     * Devuelve todos los almacenamientos en formato JSON.
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateData(){
        return response()->json(Material::with('storage')->get());
    }
}