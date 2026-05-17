<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    public function run() {
        $this->call([
            UsersSeeder::class,
            MaterialsSeeder::class,
            ActivitiesSeeder::class,
            StoragesSeeder::class,
            xStoragesSeeder::class,
            StoragesUseSeeder::class,
            StoragesReserveSeeder::class,
            StorageAssignmentsSeeder::class,
            ModificationsSeeder::class,
            MaterialActivitySeeder::class,
        ]);
    }
}
