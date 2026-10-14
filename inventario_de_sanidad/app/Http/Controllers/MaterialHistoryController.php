<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class MaterialHistoryController extends Controller {
    
    public function modifications() {
        $modifications = DB::table('modifications')
            ->join('users', 'modifications.user_id', '=', 'users.user_id')
            ->join('materials', 'modifications.material_id', '=', 'materials.material_id')
            ->select('users.first_name', 'users.last_name', 'users.email', 'users.user_type',
                    'modifications.storage', 'materials.name as material_name',
                    'modifications.units', 'modifications.action_datetime', 'modifications.storage_type')
            ->orderBy('action_datetime', 'desc')
            ->get();

        return view('materials.history.modifications', compact('modifications'));
    }

    public function useSummary() {
        $columns = [
            'materials.material_id',
            'materials.name',
            'materials.description',
            'materials.image_path',
            'storages.storage',
            'storage_use.cabinet',
            'storage_use.shelf',
            'storage_use.drawer',
            ...(auth()->user()->user_type !== 'student' ? [
                'storage_use.units',
                'storage_use.min_units',
            ] : [])
        ];

        $summary = DB::table('storages')
            ->join('materials', 'storages.material_id', '=', 'materials.material_id')
            ->join('storage_use', function ($join) {
                $join->on('storages.material_id', '=', 'storage_use.material_id')
                    ->on('storages.storage', '=', 'storage_use.storage');
            })
            ->select($columns)
            ->get();

        return view('materials.history.use', compact('summary'));
    }

    public function reserveSummary() {
        $summary = DB::table('storages')
            ->join('materials', 'storages.material_id', '=', 'materials.material_id')
            ->join('storage_reserve', function ($join) {
                $join->on('storages.material_id', '=', 'storage_reserve.material_id')
                    ->on('storages.storage', '=', 'storage_reserve.storage');
            })
            ->select(
                'materials.material_id',
                'materials.name',
                'materials.description',
                'materials.image_path',
                'storages.storage',
                'storage_reserve.cabinet',
                'storage_reserve.shelf',
                'storage_reserve.units',
                'storage_reserve.min_units'
            )
            ->get();

        return view('materials.history.reserve', compact('summary'));
    }
}
