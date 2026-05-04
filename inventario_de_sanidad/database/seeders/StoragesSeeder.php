<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoragesSeeder extends Seeder {

    public function run() {
        DB::table('storages')->insert([
            ['material_id' => 1,  'storage' => 'odontology', 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 1, 'drawer' => null, 'units' => 12, 'min_units' => 4],
            ['material_id' => 1,  'storage' => 'odontology', 'storage_type' => 'use',     'cabinet' => '1',             'shelf' => 1, 'drawer' => 1,    'units' => 7,  'min_units' => 8],
            ['material_id' => 1,  'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 1, 'drawer' => null, 'units' => 12, 'min_units' => 4],
            ['material_id' => 1,  'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '1',             'shelf' => 1, 'drawer' => 1,    'units' => 7,  'min_units' => 8],
            ['material_id' => 2,  'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 1, 'drawer' => null, 'units' => 4,  'min_units' => 6],
            ['material_id' => 2,  'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => 1,               'shelf' => 1, 'drawer' => 2,    'units' => 11, 'min_units' => 6],
            ['material_id' => 3,  'storage' => 'odontology', 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 1, 'drawer' => null, 'units' => 10, 'min_units' => 10],
            ['material_id' => 3,  'storage' => 'odontology', 'storage_type' => 'use',     'cabinet' => 1,               'shelf' => 1, 'drawer' => 1,    'units' => 15, 'min_units' => 13],
            ['material_id' => 4,  'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 1, 'drawer' => null, 'units' => 24, 'min_units' => 20],
            ['material_id' => 4,  'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 2, 'drawer' => 1,    'units' => 12, 'min_units' => 10],
            ['material_id' => 5,  'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 1, 'drawer' => null, 'units' => 44, 'min_units' => 19],
            ['material_id' => 5,  'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 2, 'drawer' => 2,    'units' => 14, 'min_units' => 16],
            ['material_id' => 6,  'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 1, 'drawer' => null, 'units' => 16, 'min_units' => 12],
            ['material_id' => 6,  'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 2, 'drawer' => 3,    'units' => 32, 'min_units' => 32],
            ['material_id' => 7,  'storage' => 'odontology', 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 1, 'drawer' => null, 'units' => 52, 'min_units' => 42],
            ['material_id' => 7,  'storage' => 'odontology', 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 3, 'drawer' => 1,    'units' => 8,  'min_units' => 14],
            ['material_id' => 7,  'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 1, 'drawer' => null, 'units' => 52, 'min_units' => 42],
            ['material_id' => 7,  'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 3, 'drawer' => 1,    'units' => 8,  'min_units' => 14],
            ['material_id' => 8,  'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 2, 'drawer' => null, 'units' => 26, 'min_units' => 23],
            ['material_id' => 8,  'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 3, 'drawer' => 2,    'units' => 27, 'min_units' => 26],
            ['material_id' => 9,  'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 2, 'drawer' => null, 'units' => 12, 'min_units' => 10],
            ['material_id' => 9,  'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 3, 'drawer' => 3,    'units' => 17, 'min_units' => 14],
            ['material_id' => 10, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 2, 'drawer' => null, 'units' => 15, 'min_units' => 11],
            ['material_id' => 10, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 4, 'drawer' => 1,    'units' => 11, 'min_units' => 12],
            ['material_id' => 11, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 2, 'drawer' => null, 'units' => 18, 'min_units' => 13],
            ['material_id' => 11, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 4, 'drawer' => 2,    'units' => 13, 'min_units' => 15],
            ['material_id' => 12, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 3, 'drawer' => null, 'units' => 19, 'min_units' => 17],
            ['material_id' => 12, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 4, 'drawer' => 3,    'units' => 11, 'min_units' => 13],
            ['material_id' => 13, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 3, 'drawer' => null, 'units' => 22, 'min_units' => 13],
            ['material_id' => 13, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 5, 'drawer' => 1,    'units' => 20, 'min_units' => 17],
            ['material_id' => 14, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 3, 'drawer' => null, 'units' => 22, 'min_units' => 21],
            ['material_id' => 14, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 5, 'drawer' => 2,    'units' => 15, 'min_units' => 13],
            ['material_id' => 15, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario gris',  'shelf' => 4, 'drawer' => null, 'units' => 23, 'min_units' => 11],
            ['material_id' => 15, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 5, 'drawer' => 3,    'units' => 24, 'min_units' => 13],
            ['material_id' => 16, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario rojo',  'shelf' => 4, 'drawer' => null, 'units' => 23, 'min_units' => 14],
            ['material_id' => 16, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 6, 'drawer' => 1,    'units' => 19, 'min_units' => 13],
            ['material_id' => 17, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario rojo',  'shelf' => 4, 'drawer' => null, 'units' => 20, 'min_units' => 14],
            ['material_id' => 17, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 6, 'drawer' => 2,    'units' => 21, 'min_units' => 17],
            ['material_id' => 18, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario rojo',  'shelf' => 5, 'drawer' => null, 'units' => 10, 'min_units' => 13],
            ['material_id' => 18, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 6, 'drawer' => 3,    'units' => 12, 'min_units' => 11],
            ['material_id' => 19, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario rojo',  'shelf' => 5, 'drawer' => null, 'units' => 35, 'min_units' => 11],
            ['material_id' => 19, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 7, 'drawer' => 1,    'units' => 32, 'min_units' => 32],
            ['material_id' => 20, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario rojo',  'shelf' => 5, 'drawer' => null, 'units' => 20, 'min_units' => 17],
            ['material_id' => 20, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 7, 'drawer' => 2,    'units' => 23, 'min_units' => 17],
            ['material_id' => 21, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario rojo',  'shelf' => 6, 'drawer' => null, 'units' => 17, 'min_units' => 12],
            ['material_id' => 21, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 7, 'drawer' => 3,    'units' => 33, 'min_units' => 51],
            ['material_id' => 22, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario rojo',  'shelf' => 6, 'drawer' => null, 'units' => 8,  'min_units' => 2 ],
            ['material_id' => 22, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 8, 'drawer' => 1,    'units' => 13, 'min_units' => 12],
            ['material_id' => 23, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario verde', 'shelf' => 6, 'drawer' => null, 'units' => 29, 'min_units' => 45],
            ['material_id' => 23, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 8, 'drawer' => 2,    'units' => 11, 'min_units' => 9 ],
            ['material_id' => 24, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario verde', 'shelf' => 7, 'drawer' => null, 'units' => 15, 'min_units' => 10],
            ['material_id' => 24, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 8, 'drawer' => 3,    'units' => 12, 'min_units' => 11],
            ['material_id' => 25, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario verde', 'shelf' => 7, 'drawer' => null, 'units' => 23, 'min_units' => 24],
            ['material_id' => 25, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 9, 'drawer' => 1,    'units' => 14, 'min_units' => 12],
            ['material_id' => 26, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario verde', 'shelf' => 7, 'drawer' => null, 'units' => 3,  'min_units' => 2],
            ['material_id' => 26, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 9, 'drawer' => 2,    'units' => 9,  'min_units' => 8 ],
            ['material_id' => 27, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario verde', 'shelf' => 8, 'drawer' => null, 'units' => 32, 'min_units' => 26],
            ['material_id' => 27, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 9, 'drawer' => 3,    'units' => 20, 'min_units' => 24],
            ['material_id' => 28, 'storage' => 'CAE'       , 'storage_type' => 'reserve', 'cabinet' => 'armario verde', 'shelf' => 8, 'drawer' => null, 'units' => 20, 'min_units' => 15],
            ['material_id' => 28, 'storage' => 'CAE'       , 'storage_type' => 'use',     'cabinet' => '2',             'shelf' => 10, 'drawer' => 1,   'units' => 20, 'min_units' => 15],
        ]);
    }
}
