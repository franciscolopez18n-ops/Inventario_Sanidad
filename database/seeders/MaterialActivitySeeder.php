<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialActivitySeeder extends Seeder {

    public function run() {
        DB::table('material_activity')->insert([
            ['activity_id' => 2, 'material_id' => 2,  'units' => 5],
            ['activity_id' => 2, 'material_id' => 5,  'units' => 5],
            ['activity_id' => 2, 'material_id' => 6,  'units' => 15],
            ['activity_id' => 3, 'material_id' => 3,  'units' => 7],
            ['activity_id' => 3, 'material_id' => 2,  'units' => 1],
            ['activity_id' => 4, 'material_id' => 1,  'units' => 10],
            ['activity_id' => 5, 'material_id' => 4,  'units' => 8],
            ['activity_id' => 6, 'material_id' => 4,  'units' => 1],
            ['activity_id' => 6, 'material_id' => 5,  'units' => 6],
            ['activity_id' => 7, 'material_id' => 6,  'units' => 4],
            ['activity_id' => 8, 'material_id' => 7,  'units' => 9],
            ['activity_id' => 9, 'material_id' => 8,  'units' => 3],
            ['activity_id' => 9, 'material_id' => 10, 'units' => 1],
            ['activity_id' => 9, 'material_id' => 1,  'units' => 2],
            ['activity_id' => 9, 'material_id' => 3,  'units' => 1],
            ['activity_id' => 10,'material_id' => 9,  'units' => 2],
            ['activity_id' => 11,'material_id' => 10, 'units' => 5],
            ['activity_id' => 12,'material_id' => 11, 'units' => 7],
            ['activity_id' => 13,'material_id' => 12, 'units' => 6],
            ['activity_id' => 14,'material_id' => 13, 'units' => 8],
            ['activity_id' => 15,'material_id' => 14, 'units' => 4],
            ['activity_id' => 16,'material_id' => 15, 'units' => 3],
            ['activity_id' => 17,'material_id' => 16, 'units' => 7],
            ['activity_id' => 18,'material_id' => 17, 'units' => 5],
            ['activity_id' => 19,'material_id' => 18, 'units' => 6],
        ]);
    }
}
