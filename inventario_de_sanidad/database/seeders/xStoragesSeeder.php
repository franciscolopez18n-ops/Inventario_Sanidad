<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class xStoragesSeeder extends Seeder {
    public function run() {
        DB::table('xstorages')->insert([
            ['material_id' => 1,  'storage' => 'odontology', 'qr_path' => null],
            ['material_id' => 1,  'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 2,  'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 3,  'storage' => 'odontology', 'qr_path' => null],
            ['material_id' => 4,  'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 5,  'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 6,  'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 7,  'storage' => 'odontology', 'qr_path' => null],
            ['material_id' => 7,  'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 8,  'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 9,  'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 10, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 11, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 12, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 13, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 14, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 15, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 16, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 17, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 18, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 19, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 20, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 21, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 22, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 23, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 24, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 25, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 26, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 27, 'storage' => 'CAE',        'qr_path' => null],
            ['material_id' => 28, 'storage' => 'CAE',        'qr_path' => null],
        ]);
    }
}