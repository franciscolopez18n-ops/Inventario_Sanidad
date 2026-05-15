<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoricalManagementController extends Controller
{
    /**
     * Devuelve un JSON con el historial de modificaciones en los materiales.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function modificationsHistoricalData()
    {
        $modifications = DB::table('modifications')
            ->join('users', 'modifications.user_id', '=', 'users.user_id')
            ->join('materials', 'modifications.material_id', '=', 'materials.material_id')
            ->select('users.first_name', 'users.last_name', 'users.email', 'users.user_type','modifications.storage', 'users.created_at',
                    'materials.name as material_name', 'modifications.units', 'modifications.action_datetime', 'modifications.storage_type')
            ->orderBy("action_datetime",'desc')
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

    /**
     * Muestra una vista dinámica de historial, según el tipo solicitado.
     *
     * @param  string  $type
     * @return \Illuminate\View\View
     */
    public function index($type)
    {
        return view("historical.$type");
    }

    /**
     * Devuelve un JSON con los materiales según el tipo de almacenamiento (use/reserve).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function historicalData()
    {
        $type = explode("=",url()->full())[1];

        $materials = DB::table('storages')
            ->join('materials', 'storages.material_id', '=', 'materials.material_id')
            ->select(
                'materials.material_id',
                'materials.name',
                'materials.description',
                'materials.image_path',
                'storages.storage',
                'storages.cabinet',
                'storages.shelf',
                'storages.units',
                'storages.min_units'
            )
            ->where('storages.storage_type',$type )
            ->get();
        return response()->json($materials);
    }
}
