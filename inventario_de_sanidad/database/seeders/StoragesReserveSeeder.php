<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoragesReserveSeeder extends Seeder {
    public function run() {
        DB::table('storage_reserve')->insert([
            ['material_id' => 1,  'storage' => 'odontology', 'units' => 12, 'min_units' => 4,  'cabinet' => 'armario gris',  'shelf' => 1],
            ['material_id' => 1,  'storage' => 'CAE',        'units' => 12, 'min_units' => 4,  'cabinet' => 'armario gris',  'shelf' => 1],
            ['material_id' => 2,  'storage' => 'CAE',        'units' => 4,  'min_units' => 6,  'cabinet' => 'armario gris',  'shelf' => 1],
            ['material_id' => 3,  'storage' => 'odontology', 'units' => 10, 'min_units' => 10, 'cabinet' => 'armario gris',  'shelf' => 1],
            ['material_id' => 4,  'storage' => 'CAE',        'units' => 24, 'min_units' => 20, 'cabinet' => 'armario gris',  'shelf' => 1],
            ['material_id' => 5,  'storage' => 'CAE',        'units' => 44, 'min_units' => 19, 'cabinet' => 'armario gris',  'shelf' => 1],
            ['material_id' => 6,  'storage' => 'CAE',        'units' => 16, 'min_units' => 12, 'cabinet' => 'armario gris',  'shelf' => 1],
            ['material_id' => 7,  'storage' => 'odontology', 'units' => 52, 'min_units' => 42, 'cabinet' => 'armario gris',  'shelf' => 1],
            ['material_id' => 7,  'storage' => 'CAE',        'units' => 52, 'min_units' => 42, 'cabinet' => 'armario gris',  'shelf' => 1],
            ['material_id' => 8,  'storage' => 'CAE',        'units' => 26, 'min_units' => 23, 'cabinet' => 'armario gris',  'shelf' => 2],
            ['material_id' => 9,  'storage' => 'CAE',        'units' => 12, 'min_units' => 10, 'cabinet' => 'armario gris',  'shelf' => 2],
            ['material_id' => 10, 'storage' => 'CAE',        'units' => 15, 'min_units' => 11, 'cabinet' => 'armario gris',  'shelf' => 2],
            ['material_id' => 11, 'storage' => 'CAE',        'units' => 18, 'min_units' => 13, 'cabinet' => 'armario gris',  'shelf' => 2],
            ['material_id' => 12, 'storage' => 'CAE',        'units' => 19, 'min_units' => 17, 'cabinet' => 'armario gris',  'shelf' => 3],
            ['material_id' => 13, 'storage' => 'CAE',        'units' => 22, 'min_units' => 13, 'cabinet' => 'armario gris',  'shelf' => 3],
            ['material_id' => 14, 'storage' => 'CAE',        'units' => 22, 'min_units' => 21, 'cabinet' => 'armario gris',  'shelf' => 3],
            ['material_id' => 15, 'storage' => 'CAE',        'units' => 23, 'min_units' => 11, 'cabinet' => 'armario gris',  'shelf' => 4],
            ['material_id' => 16, 'storage' => 'CAE',        'units' => 23, 'min_units' => 14, 'cabinet' => 'armario rojo',  'shelf' => 4],
            ['material_id' => 17, 'storage' => 'CAE',        'units' => 20, 'min_units' => 14, 'cabinet' => 'armario rojo',  'shelf' => 4],
            ['material_id' => 18, 'storage' => 'CAE',        'units' => 10, 'min_units' => 13, 'cabinet' => 'armario rojo',  'shelf' => 5],
            ['material_id' => 19, 'storage' => 'CAE',        'units' => 35, 'min_units' => 11, 'cabinet' => 'armario rojo',  'shelf' => 5],
            ['material_id' => 20, 'storage' => 'CAE',        'units' => 20, 'min_units' => 17, 'cabinet' => 'armario rojo',  'shelf' => 5],
            ['material_id' => 21, 'storage' => 'CAE',        'units' => 17, 'min_units' => 12, 'cabinet' => 'armario rojo',  'shelf' => 6],
            ['material_id' => 22, 'storage' => 'CAE',        'units' => 8,  'min_units' => 2,  'cabinet' => 'armario rojo',  'shelf' => 6],
            ['material_id' => 23, 'storage' => 'CAE',        'units' => 29, 'min_units' => 45, 'cabinet' => 'armario verde', 'shelf' => 6],
            ['material_id' => 24, 'storage' => 'CAE',        'units' => 15, 'min_units' => 10, 'cabinet' => 'armario verde', 'shelf' => 7],
            ['material_id' => 25, 'storage' => 'CAE',        'units' => 23, 'min_units' => 24, 'cabinet' => 'armario verde', 'shelf' => 7],
            ['material_id' => 26, 'storage' => 'CAE',        'units' => 3,  'min_units' => 2,  'cabinet' => 'armario verde', 'shelf' => 7],
            ['material_id' => 27, 'storage' => 'CAE',        'units' => 32, 'min_units' => 26, 'cabinet' => 'armario verde', 'shelf' => 8],
            ['material_id' => 28, 'storage' => 'CAE',        'units' => 20, 'min_units' => 15, 'cabinet' => 'armario verde', 'shelf' => 8],
        ]);
    }
}