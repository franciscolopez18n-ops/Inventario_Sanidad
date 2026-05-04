<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModificationsSeeder extends Seeder {

    public function run() {
        DB::table('modifications')->insert([
            ['user_id' => 8, 'material_id' => 1, 'storage' => 'CAE', 'storage_type' => 'reserve',  'units' => 10, 'action_datetime' => now()],
            ['user_id' => 6, 'material_id' => 2, 'storage' => 'CAE', 'storage_type' => 'use', 'units' => -5, 'action_datetime' => now()->subDays(5)],
            ['user_id' => 9, 'material_id' => 1, 'storage' => 'CAE', 'storage_type' => 'reserve', 'units' => -3, 'action_datetime' => now()->subDays(10)],
            ['user_id' => 8, 'material_id' => 1, 'storage' => 'CAE', 'storage_type' => 'reserve', 'units' => 15, 'action_datetime' => now()->subDays(3)],
            ['user_id' => 7, 'material_id' => 2, 'storage' => 'CAE', 'storage_type' => 'use',  'units' => -5, 'action_datetime' => now()->subDays(7)],
            ['user_id' => 9, 'material_id' => 1, 'storage' => 'CAE', 'storage_type' => 'reserve', 'units' => -8, 'action_datetime' => now()->subDays(2)],
            ['user_id' => 9, 'material_id' => 1, 'storage' => 'odontology', 'storage_type' => 'reserve',  'units' => 12, 'action_datetime' => now()->subDays(4)],
            ['user_id' => 6, 'material_id' => 3, 'storage' => 'odontology', 'storage_type' => 'use', 'units' => -4, 'action_datetime' => now()->subDays(6)],
            ['user_id' => 9, 'material_id' => 7, 'storage' => 'odontology', 'storage_type' => 'use', 'units' => 9, 'action_datetime' => now()->subDays(1)],
        ]);
    }
}
