<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoragesUseSeeder extends Seeder {
    public function run() {
        DB::table('storage_use')->insert([
            ['material_id' => 1,  'storage' => 'odontology', 'units' => 7,  'min_units' => 8,  'cabinet' => 1, 'shelf' => 1,  'drawer' => 1],
            ['material_id' => 1,  'storage' => 'CAE',        'units' => 7,  'min_units' => 8,  'cabinet' => 1, 'shelf' => 1,  'drawer' => 1],
            ['material_id' => 2,  'storage' => 'CAE',        'units' => 11, 'min_units' => 6,  'cabinet' => 1, 'shelf' => 1,  'drawer' => 2],
            ['material_id' => 3,  'storage' => 'odontology', 'units' => 15, 'min_units' => 13, 'cabinet' => 1, 'shelf' => 1,  'drawer' => 1],
            ['material_id' => 4,  'storage' => 'CAE',        'units' => 12, 'min_units' => 10, 'cabinet' => 2, 'shelf' => 2,  'drawer' => 1],
            ['material_id' => 5,  'storage' => 'CAE',        'units' => 14, 'min_units' => 16, 'cabinet' => 2, 'shelf' => 2,  'drawer' => 2],
            ['material_id' => 6,  'storage' => 'CAE',        'units' => 32, 'min_units' => 32, 'cabinet' => 2, 'shelf' => 2,  'drawer' => 3],
            ['material_id' => 7,  'storage' => 'odontology', 'units' => 8,  'min_units' => 14, 'cabinet' => 2, 'shelf' => 3,  'drawer' => 1],
            ['material_id' => 7,  'storage' => 'CAE',        'units' => 8,  'min_units' => 14, 'cabinet' => 2, 'shelf' => 3,  'drawer' => 1],
            ['material_id' => 8,  'storage' => 'CAE',        'units' => 27, 'min_units' => 26, 'cabinet' => 2, 'shelf' => 3,  'drawer' => 2],
            ['material_id' => 9,  'storage' => 'CAE',        'units' => 17, 'min_units' => 14, 'cabinet' => 2, 'shelf' => 3,  'drawer' => 3],
            ['material_id' => 10, 'storage' => 'CAE',        'units' => 11, 'min_units' => 12, 'cabinet' => 2, 'shelf' => 4,  'drawer' => 1],
            ['material_id' => 11, 'storage' => 'CAE',        'units' => 13, 'min_units' => 15, 'cabinet' => 2, 'shelf' => 4,  'drawer' => 2],
            ['material_id' => 12, 'storage' => 'CAE',        'units' => 11, 'min_units' => 13, 'cabinet' => 2, 'shelf' => 4,  'drawer' => 3],
            ['material_id' => 13, 'storage' => 'CAE',        'units' => 20, 'min_units' => 17, 'cabinet' => 2, 'shelf' => 5,  'drawer' => 1],
            ['material_id' => 14, 'storage' => 'CAE',        'units' => 15, 'min_units' => 13, 'cabinet' => 2, 'shelf' => 5,  'drawer' => 2],
            ['material_id' => 15, 'storage' => 'CAE',        'units' => 24, 'min_units' => 13, 'cabinet' => 2, 'shelf' => 5,  'drawer' => 3],
            ['material_id' => 16, 'storage' => 'CAE',        'units' => 19, 'min_units' => 13, 'cabinet' => 2, 'shelf' => 6,  'drawer' => 1],
            ['material_id' => 17, 'storage' => 'CAE',        'units' => 21, 'min_units' => 17, 'cabinet' => 2, 'shelf' => 6,  'drawer' => 2],
            ['material_id' => 18, 'storage' => 'CAE',        'units' => 12, 'min_units' => 11, 'cabinet' => 2, 'shelf' => 6,  'drawer' => 3],
            ['material_id' => 19, 'storage' => 'CAE',        'units' => 32, 'min_units' => 32, 'cabinet' => 2, 'shelf' => 7,  'drawer' => 1],
            ['material_id' => 20, 'storage' => 'CAE',        'units' => 23, 'min_units' => 17, 'cabinet' => 2, 'shelf' => 7,  'drawer' => 2],
            ['material_id' => 21, 'storage' => 'CAE',        'units' => 33, 'min_units' => 51, 'cabinet' => 2, 'shelf' => 7,  'drawer' => 3],
            ['material_id' => 22, 'storage' => 'CAE',        'units' => 13, 'min_units' => 12, 'cabinet' => 2, 'shelf' => 8,  'drawer' => 1],
            ['material_id' => 23, 'storage' => 'CAE',        'units' => 11, 'min_units' => 9,  'cabinet' => 2, 'shelf' => 8,  'drawer' => 2],
            ['material_id' => 24, 'storage' => 'CAE',        'units' => 12, 'min_units' => 11, 'cabinet' => 2, 'shelf' => 8,  'drawer' => 3],
            ['material_id' => 25, 'storage' => 'CAE',        'units' => 14, 'min_units' => 12, 'cabinet' => 2, 'shelf' => 9,  'drawer' => 1],
            ['material_id' => 26, 'storage' => 'CAE',        'units' => 9,  'min_units' => 8,  'cabinet' => 2, 'shelf' => 9,  'drawer' => 2],
            ['material_id' => 27, 'storage' => 'CAE',        'units' => 20, 'min_units' => 24, 'cabinet' => 2, 'shelf' => 9,  'drawer' => 3],
            ['material_id' => 28, 'storage' => 'CAE',        'units' => 20, 'min_units' => 15, 'cabinet' => 2, 'shelf' => 10, 'drawer' => 1],
        ]);
    }
}