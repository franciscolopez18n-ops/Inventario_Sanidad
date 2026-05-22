<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\FlashType;
use App\Traits\HasStorageOperations;
use App\Models\Material;
use App\Models\Storage;
use App\Models\StorageAssignment;
use App\Models\StorageUse;
use App\Models\StorageReserve;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage as StorageFacades;

class MaterialManagementController extends Controller {
    use HasStorageOperations;
    
    public function updateIndex() {
        return view('materials.update.index');
    }

    public function updateManualEdit(Material $material) {
        $storages = Storage::where('material_id', $material->material_id)->get();

        return view('materials.update.edit')
            ->with('material', $material)
            ->with('storages', $storages);
    }

    public function updateQrEdit(Material $material, string $storage) {
        $storageRecord = Storage::where('material_id', $material->material_id)
            ->where('storage', $storage)
            ->firstOrFail();

        return view('materials.update.edit')
            ->with('material', $material)
            ->with('storages', collect([$storageRecord]));
    }

    // Actualiza los datos de un material y/o su almacenamiento
    public function updateSubmit(Material $material, Request $request) {
        // Almacenes enviados en el request
        $storageKeys = array_keys($request->except(['name', 'description', 'image', '_token']));

        $rules = [
            'name'        => 'required|string|max:60',
            'description' => 'required|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png|max:4096',
        ];
        
        $messages = [
            'name.required'        => 'Debes introducir el nombre del material.',
            'name.max'             => 'El nombre no puede superar 60 caracteres.',
            'description.required' => 'Debes introducir la descripción del material.',
            'description.max'      => 'La descripción no puede superar 100 caracteres.',
            'image.image'          => 'El archivo debe ser una imagen.',
            'image.mimes'          => 'Solo se aceptan jpeg o png.',
            'image.max'            => 'La imagen no puede superar 4 MB.',
        ];
        
        foreach ($storageKeys as $storage) {
            $rules["$storage.use_units"]         = 'required|integer|min:0';
            $rules["$storage.use_min_units"]     = 'required|integer|min:0';
            $rules["$storage.use_cabinet"]       = 'required|integer|min:1';
            $rules["$storage.use_shelf"]         = 'required|integer|min:1';
            $rules["$storage.use_drawer"]        = 'required|integer|min:1';
            $rules["$storage.reserve_units"]     = 'required|integer|min:0';
            $rules["$storage.reserve_min_units"] = 'required|integer|min:0';
            $rules["$storage.reserve_cabinet"]   = 'required|string';
            $rules["$storage.reserve_shelf"]     = 'required|integer|min:1';
            $rules["$storage.onlyReserve"]       = 'nullable|boolean';
        
            $messages["$storage.use_units.required"]       = 'La cantidad de uso es obligatoria.';
            $messages["$storage.use_units.integer"]        = 'La cantidad de uso debe ser un número entero.';
            $messages["$storage.use_units.min"]            = 'La cantidad de uso no puede ser negativa.';
            $messages["$storage.use_min_units.required"]   = 'La cantidad mínima de uso es obligatoria.';
            $messages["$storage.use_min_units.integer"]    = 'La cantidad mínima de uso debe ser un número entero.';
            $messages["$storage.use_min_units.min"]        = 'La cantidad mínima de uso no puede ser negativa.';
            $messages["$storage.use_cabinet.required"]     = 'El armario de uso es obligatorio.';
            $messages["$storage.use_cabinet.integer"]      = 'El armario de uso debe ser un número entero.';
            $messages["$storage.use_cabinet.min"]          = 'El armario de uso debe ser mayor que 0.';
            $messages["$storage.use_shelf.required"]       = 'La balda de uso es obligatoria.';
            $messages["$storage.use_shelf.integer"]        = 'La balda de uso debe ser un número entero.';
            $messages["$storage.use_shelf.min"]            = 'La balda de uso debe ser mayor que 0.';
            $messages["$storage.use_drawer.required"]      = 'El cajón de uso es obligatorio.';
            $messages["$storage.use_drawer.integer"]       = 'El cajón de uso debe ser un número entero.';
            $messages["$storage.use_drawer.min"]           = 'El cajón de uso debe ser mayor que 0.';
            $messages["$storage.reserve_units.required"]   = 'La cantidad de reserva es obligatoria.';
            $messages["$storage.reserve_units.integer"]    = 'La cantidad de reserva debe ser un número entero.';
            $messages["$storage.reserve_units.min"]        = 'La cantidad de reserva no puede ser negativa.';
            $messages["$storage.reserve_min_units.required"] = 'La cantidad mínima de reserva es obligatoria.';
            $messages["$storage.reserve_min_units.integer"]  = 'La cantidad mínima de reserva debe ser un número entero.';
            $messages["$storage.reserve_min_units.min"]      = 'La cantidad mínima de reserva no puede ser negativa.';
            $messages["$storage.reserve_cabinet.required"] = 'El armario de reserva es obligatorio.';
            $messages["$storage.reserve_shelf.required"]   = 'La balda de reserva es obligatoria.';
            $messages["$storage.reserve_shelf.integer"]    = 'La balda de reserva debe ser un número entero.';
            $messages["$storage.reserve_shelf.min"]        = 'La balda de reserva debe ser mayor que 0.';
        }
        
        $validated = $request->validate($rules, $messages);

        $oldPath = $material->image_path; // Se guarda la ruta antigua de la imagen del material.
        // Si se ha subido una nueva imagen del material, se guarda en el disco 'public' en la carpeta 'materials'.
        $newPath = ($request->hasFile('image'))
            ? $request->file('image')->store('materials','public')
            : null;

        try {
            $updated = false;

            DB::transaction(function () use (
                &$updated,
                $material,
                $validated,
                $newPath,
                $oldPath,
                $storageKeys
            ) {
                // Actualizar material solo si cambió
                if (
                    $validated['name'] != $material->name ||
                    $validated['description'] != $material->description ||
                    $newPath !== null
                ) {
                    $updated = true;

                    $material->update([
                        'name'        => $validated['name'],
                        'description' => $validated['description'],
                        'image_path'  => $newPath ?? $oldPath,
                    ]);
    
                    if ($newPath && $oldPath) {
                        if (!StorageFacades::disk('public')->delete($oldPath)) {
                            throw new \Exception('No se pudo eliminar la imagen anterior del material.');
                        }
                    }
                }

                // Actualizar almacenamiento por cada almacén
                foreach ($storageKeys as $storage) {
                    $useRecord = StorageUse::where('material_id', $material->material_id)->where('storage', $storage)->first();
                    $reserveRecord = StorageReserve::where('material_id', $material->material_id)->where('storage', $storage)->first();
 
                    if (!($validated[$storage] ?? null) || !$useRecord || !$reserveRecord) continue;

                    $storageChanged = !empty($validated[$storage]['onlyReserve'])
                        ? (
                            $validated[$storage]['reserve_units']     != $reserveRecord->units ||
                            $validated[$storage]['reserve_min_units'] != $reserveRecord->min_units ||
                            $validated[$storage]['reserve_cabinet']   != $reserveRecord->cabinet ||
                            $validated[$storage]['reserve_shelf']     != $reserveRecord->shelf
                        )
                        : (
                            $validated[$storage]['use_units']         != $useRecord->units ||
                            $validated[$storage]['use_min_units']     != $useRecord->min_units ||
                            $validated[$storage]['use_cabinet']       != $useRecord->cabinet ||
                            $validated[$storage]['use_shelf']         != $useRecord->shelf ||
                            $validated[$storage]['use_drawer']        != $useRecord->drawer ||
                            $validated[$storage]['reserve_units']     != $reserveRecord->units ||
                            $validated[$storage]['reserve_min_units'] != $reserveRecord->min_units ||
                            $validated[$storage]['reserve_cabinet']   != $reserveRecord->cabinet ||
                            $validated[$storage]['reserve_shelf']     != $reserveRecord->shelf
                        );

                    // Actualizar almacenamiento solo si cambió
                    if ($storageChanged) {
                        $updated = true;

                        if (!empty($validated[$storage]['onlyReserve'])) {
                            // Modo reposición: solo se actualiza reserva

                            // Se calcula la diferencia de unidades para "reserve".
                            $differenceReserve = $validated[$storage]['reserve_units'] - $reserveRecord->units;
                        
                            // Actualiza el almacenamiento de reserva.
                            StorageReserve::where('material_id', $material->material_id)
                                ->where('storage', $storage)
                                ->update([
                                    'units'     => $validated[$storage]['reserve_units'],
                                    'min_units' => $validated[$storage]['reserve_min_units'],
                                    'cabinet'   => $validated[$storage]['reserve_cabinet'],
                                    'shelf'     => $validated[$storage]['reserve_shelf'],
                                ]);
 
                            // Registra la modificación realizada en el almacenamiento de reserva, almacenando la diferencia calculada.
                            if ($differenceReserve != 0) {
                                $this->storeEditInModification($reserveRecord->getAssignment(), $differenceReserve);
                            }
                        } else {
                            // Modo de distribución

                            $newUseUnits = $validated[$storage]['use_units'];
                            $newReserveUnits = $validated[$storage]['reserve_units'];

                            // Se calculan las diferencias en unidades para el almacenamiento de uso y reserva.
                            $differenceUse = $newUseUnits - $useRecord->units;
                            $differenceReserve = $newReserveUnits - $reserveRecord->units;

                            // Si cambia ambas unidades, no se realiza el cambio.
                            if ($differenceUse != 0 && $differenceReserve != 0) {
                                throw new \Exception('Solo puedes modificar una de las dos cantidades; el otro valor se ajustará automáticamente.');
                            }

                            if ($differenceUse != 0) {
                                // Si se modifica la cantidad de uso, se ajusta automáticamente la de reserva.
                                $newReserveUnits = $reserveRecord->units - $differenceUse;
                                if ($newReserveUnits < 0) {
                                    throw new \Exception('No puedes transferir más unidades de las que hay en reserva.');
                                }
                            } else if ($differenceReserve != 0) {
                                // Si se modifica la cantidad de reserva, se ajusta automáticamente la de uso.
                                $newUseUnits = $useRecord->units - $differenceReserve;
                                if ($newUseUnits < 0) {
                                    throw new \Exception('No puedes transferir más unidades de las que hay en uso.');
                                }
                            }

                            // Actualiza el almacenamiento de uso.
                            StorageUse::where('material_id', $material->material_id)
                                ->where('storage', $storage)
                                ->update([
                                    'units' => $newUseUnits,
                                    'min_units' => $validated[$storage]['use_min_units'],
                                    'cabinet' => $validated[$storage]['use_cabinet'],
                                    'shelf' => $validated[$storage]['use_shelf'],
                                    'drawer' => $validated[$storage]['use_drawer']
                                ]);
                
                            // Actualiza el almacenamiento de reserva.
                            StorageReserve::where('material_id', $material->material_id)
                                ->where('storage', $storage)
                                ->update([
                                    'units'     => $newReserveUnits,
                                    'min_units' => $validated[$storage]['reserve_min_units'],
                                    'cabinet'   => $validated[$storage]['reserve_cabinet'],
                                    'shelf'     => $validated[$storage]['reserve_shelf'],
                                ]);
                    
                            // Registra las modificaciones correspondientes según cuál de las cantidades haya cambiado.
                            if ($differenceUse != 0) {
                                // Si se modificó la cantidad de uso, se registra la diferencia positiva en "use"
                                $this->storeEditInModification($useRecord->getAssignment(), $differenceUse);
                                // y la diferencia negativa en "reserve" para mantener el balance.
                                $this->storeEditInModification($reserveRecord->getAssignment(), -$differenceUse);
                            } else if ($differenceReserve != 0) {
                                // Si se modificó la cantidad de reserva, se registra la diferencia positiva en "reserve"
                                $this->storeEditInModification($reserveRecord->getAssignment(), $differenceReserve);
                                // y la diferencia negativa en "use" para mantener el balance.
                                $this->storeEditInModification($useRecord->getAssignment(), -$differenceReserve);
                            }
                        }
                    }
                }
            });

            if ($updated) {
                foreach ($storageKeys as $storage) {
                    // Nota: no es un capricho volver a recuperar los registros, porque refresh() no funciona con clave compuesta
                    $useRecord = StorageUse::where('material_id', $material->material_id)->where('storage', $storage)->first();
                    $reserveRecord = StorageReserve::where('material_id', $material->material_id)->where('storage', $storage)->first();
                    
                    // Una vez la transacción finalizó, se comprueba que las unidades actualizadas no sean menores que el mínimo de unidades.
                    // Sino, se envía un correo a los administradores avisándoles.
                    if ($useRecord) $this->checkUnits($useRecord);
                    if ($reserveRecord) $this->checkUnits($reserveRecord);
                }
            } else {
                return back()->with(FlashType::INFO, 'Nada que actualizar.');
            }

            return back()->with(FlashType::SUCCESS, 'Material actualizado correctamente.');

        } catch(\Exception $e) {
            if ($newPath && StorageFacades::disk('public')->exists($newPath)) {
                StorageFacades::disk('public')->delete($newPath);
            }
    
            return back()->withInput()->with(FlashType::ERROR, 'Error al actualizar: ' . $e->getMessage());
        }
    }

    // Elimina un material y su almacenamiento
    public function updateDestroy(Material $material) {
        try {
            // Verifica si el material aún existe en la base de datos mediante su ID.
            if (!Material::find($material->material_id)) {
                // Si no existe (puede haber sido eliminado previamente), muestra advertencia.
                return back()->with(FlashType::WARNING, 'El material no existe o ya ha sido eliminado.');
            }

            // Elimina la imagen del material.
            $path = $material->image_path;
            if (!empty($path)) {
                StorageFacades::disk('public')->delete($path);
            }

            // Elimina las imágenes de los QR asociados al material.
            foreach ($material->storages as $storage) {
                if ($storage->qr_path) {
                    StorageFacades::disk('local')->delete($storage->qr_path);
                }
            }

            $material->delete();

            return back()->with(FlashType::SUCCESS, 'Material eliminado correctamente.');

        } catch (\Exception $e) {
            return back()->with(FlashType::ERROR, 'Error al eliminar el material: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la vista para crear (dar de alta) nuevos materiales.
     *
     * @return \Illuminate\View\View
     */
    public function createForm() {
        return view('materials.create');
    }

    /**
     * Devuelve en JSON la lista de todos los materiales ordenados por ID.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function materialsData() {
        return response()->json(Material::orderBy('material_id')->get());
    }

    /**
     * Da de alta (guarda) en batch los materiales almacenados temporalmente en la cookie 'materialsAddBasket'.
     * Realiza toda la operación en una transacción, si hay error no se inserta nada.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBatch(Request $request) {
        // Decodifica la cookie desde JSON a un array asociativo. Si no es válido, usa un array vacío que será rechazado.
        // Los métodos de Laravel esperan cookies encriptadas por ellos mismos, por lo que hay que leerla en crudo
        $basket = json_decode(urldecode($_COOKIE['materialsAddBasket'] ?? '[]'), true);
        
        if (empty($basket)) {
            return back()->with(FlashType::ERROR, 'No hay materiales añadidos en la cesta para dar de alta.');
        }

        // Comprobaciones de seguridad por si el frontend fue manipulado.
        foreach ($basket as $material) {
            $validator = validator($material, [
                'name' => 'required|string',
                'description' => 'required|string',
                'storage' => 'required|in:CAE,odontology,ambos',
                'image_temp' => 'nullable|string',

                'units_use' => 'required|numeric|min:0',
                'min_units_use' => 'required|numeric|min:0',
                'cabinet_use' => 'required|numeric|min:1',
                'shelf_use' => 'required|numeric|min:1',
                'drawer_use' => 'required|numeric|min:1',

                'units_reserve' => 'required|numeric|min:0',
                'min_units_reserve' => 'required|numeric|min:0',
                'cabinet_reserve' => 'required|string',
                'shelf_reserve' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                return back()->with(FlashType::ERROR, 'Los datos de la cesta no son válidos.');
            }
        }

        // Array para almacenar información de imágenes que deben moverse tras la transacción.
        $imagesMaterials = [];

        try {
            // Inicia una transacción de base de datos: si algo falla, se revierte todo.
            DB::transaction(function () use ($basket, &$imagesMaterials) {
                // Itera sobre cada material en la cesta.
                foreach ($basket as $materialData) {
                    // Crea una nueva instancia del Material.
                    $material = new Material();
                    $material->name = $materialData["name"];
                    $material->description = $materialData["description"];
                    $material->image_path = null; // Se definirá después si hay imagen.
                    $material->save(); // Guarda el material en la base de datos.

                    // Si hay imagen temporal, guarda info para moverla después de la transacción.
                    if (!empty($materialData["image_temp"])) {
                        $imageName = pathinfo($materialData["image_temp"], PATHINFO_BASENAME);
                        $imagesMaterials[] = [
                            'material_id'   =>  $material->material_id,
                            'image_temp'    =>  $materialData["image_temp"],
                            'image_path'    =>  "materials/{$imageName}",
                        ];
                    }

                    // Registra el almacenamiento del material (en uso o reserva).
                    $this->storeMaterialInStorage($material, $materialData);
                }
            });

            // Si la transacción fue exitosa, elimina la cookie con los materiales temporales.
            Cookie::queue(Cookie::forget('materialsAddBasket'));

        } catch (\Exception $e) {
            // Si hay error en la transacción, muestra mensaje de error.
            return back()->with(FlashType::ERROR, 'Error al insertar los materiales: ' . $e->getMessage());
        }

        $failedMaterials = [];

        // Intenta mover cada imagen temporal a su ubicación definitiva.
        foreach ($imagesMaterials as $imageData) {
            try {
                $moved = StorageFacades::disk('public')->move($imageData["image_temp"], $imageData["image_path"]);

                if (!$moved) {
                    // Si no se pudo mover, se agrega el ID del material a la lista de fallidos.
                    $failedMaterials[] = $imageData["material_id"];
                } else {
                    // Si se movió correctamente, se actualiza el path de la imagen en la BD.
                    Material::where('material_id', $imageData["material_id"])->update(['image_path' =>  $imageData["image_path"]]);
                }
            } catch (\Exception $e) {
                // En caso de excepción, intenta recuperar el nombre del material y lo agrega a la lista de fallidos.
                $materialName = Material::where('material_id', $imageData["material_id"])->get('name');
                $failedMaterials[] = $materialName;
            }
        }

        // Limpia el directorio temporal de imágenes.
        StorageFacades::disk('public')->deleteDirectory('temp');

        // Si no hubo errores al mover imágenes, muestra mensaje de éxito.
        if (empty($failedMaterials)) {
            return back()->with(FlashType::SUCCESS, 'Materiales incorporados correctamente.');
        } else {
            // Si hubo fallos al mover imágenes, muestra advertencia con los materiales afectados.
            $failedList = implode(', ', $failedMaterials);
            return back()->with(FlashType::WARNING, "Error al mover imágenes para los siguientes materiales: $failedList. Los demás se incorporaron correctamente.");
        }
    }

    /**
     * Registra el almacenamiento en los almacenes 'CAE' u 'odontology' para un material en los tipos 'use' y 'reserve'.
     *
     * @param Material $material           Instancia del material recién creado o existente.
     * @param array $materialData          Datos de entrada con la información de almacenamiento, incluyendo ubicación y unidades.
     */
    private function storeMaterialInStorage(Material $material, array $materialData) {
        // Determina en qué almacenes (CAE, odontology o ambos) se debe registrar el material.
        switch ($materialData['storage']) {
            case 'CAE':
                $storages = ['CAE']; // Solo en CAE.
                break;
            case 'odontology':
                $storages = ['odontology']; // Solo en odontología.
                break;
            case 'ambos':
            default:
                $storages = ['CAE', 'odontology']; // En ambos almacenes.
                break;
        }

        // Recorre cada almacén seleccionado...
        foreach ($storages as $storage) {
            Storage::create([
                'material_id' => $material->material_id,
                'storage'     => $storage,
                'qr_path'     => Storage::generateQr($material->material_id, $storage),
            ]);

            StorageAssignment::create([
                'material_id'  => $material->material_id,
                'storage'      => $storage,
                'storage_type' => 'use',
            ]);

            StorageAssignment::create([
                'material_id'  => $material->material_id,
                'storage'      => $storage,
                'storage_type' => 'reserve',
            ]);

            // ...y para cada tipo de almacenamiento (uso y reserva) crea un nuevo registro con la información correspondiente.

            StorageUse::create([
                'material_id' => $material->material_id,
                'storage'     => $storage,
                'units'       => $materialData['units_use'],
                'min_units'   => $materialData['min_units_use'],
                'cabinet'     => $materialData['cabinet_use'],
                'shelf'       => $materialData['shelf_use'],
                'drawer'      => $materialData['drawer_use'],
            ]);

            StorageReserve::create([
                'material_id' => $material->material_id,
                'storage'     => $storage,
                'units'       => $materialData['units_reserve'],
                'min_units'   => $materialData['min_units_reserve'],
                'cabinet'     => $materialData['cabinet_reserve'],
                'shelf'       => $materialData['shelf_reserve'],
            ]);
        }
    }

    /**
     * Sube una imagen temporal y devuelve su ruta para uso posterior.
     *
     * @param Request $request  Petición HTTP que contiene la imagen enviada desde un formulario o cliente.
     * @return \Illuminate\Http\JsonResponse  Respuesta en formato JSON con la ruta temporal de la imagen.
     */
    public function uploadTemp(Request $request) {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png|max:4096'
        ]);

        // Se almacena la imagen en la carpeta 'temp' del disco 'public' y devuelve la ruta relativa dentro del disco.
        $tempPath = $request->file('image')->store('temp', 'public');

        // Se retorna la ruta de la imagen temporal en una respuesta JSON
        // Si por alguna razón no se generó la ruta, se devuelve 'null'.
        return response()->json([
            'tempPath' => $tempPath ?? null
        ]);
    }
}
