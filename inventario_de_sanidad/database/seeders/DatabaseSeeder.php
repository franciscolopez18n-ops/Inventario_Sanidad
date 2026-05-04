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
            ModificationsSeeder::class,
            MaterialActivitySeeder::class,
        ]);
    }
}
