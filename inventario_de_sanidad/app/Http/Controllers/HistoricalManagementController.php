<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class HistoricalManagementController extends Controller {
    /**
     * Devuelve un JSON con el historial de modificaciones en los materiales.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function modificationsHistoricalData() {
        $modifications = DB::table('modifications')
            ->join('users', 'modifications.user_id', '=', 'users.user_id')
            ->join('materials', 'modifications.material_id', '=', 'materials.material_id')
            ->select('users.first_name', 'users.last_name', 'users.email', 'users.user_type',
                    'modifications.storage', 'materials.name as material_name',
                    'modifications.units', 'modifications.action_datetime', 'modifications.storage_type')
            ->orderBy('action_datetime', 'desc')
            ->get();

        return response()->json($modifications);
    }

    /**
     * Muestra la vista del historial de modificaciones.
     *
     * @return \Illuminate\View\View
     */
    public function showModificationsHistorical()
    {
        return view('historical.modificationsHistorical');
    }

    public function use() {
        return view('historical.use');
    }

    public function reserve() {
        return view('historical.reserve');
    }

    /**
     * Devuelve un JSON con los materiales según el tipo de almacenamiento (use/reserve).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function historicalData() {
        $type = explode("=", URL::full())[1];

        $table = $type === 'use' ? 'storage_use' : 'storage_reserve';

        $query = DB::table('storages')
            ->join('materials', 'storages.material_id', '=', 'materials.material_id')
            ->join($table, function ($join) use ($table) {
                $join->on('storages.material_id', '=', "$table.material_id")
                    ->on('storages.storage', '=', "$table.storage");
            })
            ->select(
                'materials.material_id',
                'materials.name',
                'materials.description',
                'materials.image_path',
                'storages.storage',
                "$table.cabinet",
                "$table.shelf",
                "$table.units",
                "$table.min_units"
            );

        if ($type === 'use') {
            $query->addSelect("$table.drawer");
        }

        $materials = $query->get();

        return response()->json($materials);
    }
}
