<?php

namespace App\Traits;

use App\Contracts\StockStorage;
use App\Models\Modification;
use App\Models\User;
use App\Models\StorageAssignment;
use Carbon\Carbon;
use App\Mail\LowStockAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

trait HasStorageOperations {

        /**
     * Registra las unidades modificadas de un material y almacenamiento específico.
     * 
     * Esta función crea un nuevo registro en la tabla de modificaciones (`modifications`)
     * para dejar constancia de un cambio en las unidades de un material en un tipo 
     * específico de almacenamiento (uso o reserva), junto con la ubicación y el usuario
     */

    private function storeEditInModification(StorageAssignment $assignment, int $units) {
        Modification::create([
            'user_id'         => Auth::id(),
            'material_id'     => $assignment->material_id,
            'storage_type'    => $assignment->storage_type,
            'storage'         => $assignment->storage,
            'units'           => $units,
            'action_datetime' => Carbon::now('Europe/Madrid'),
        ]);
    }

    /**
     * Comprueba las unidades de un material en un tipo de almacenamiento.
     *
     * Si las unidades disponibles son menores que el mínimo definido, 
     * se envía una advertencia por correo electrónico al administrador.
     */

    private function checkUnits(StockStorage $record) {
        if ($record->getUnits() < $record->getMinUnits()) {
            $adminEmails = User::where('user_type', 'admin')->pluck('email');
            Mail::to($adminEmails)->send(new LowStockAlert($record->getAssignment()));
        }
    }
}