<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersSeeder extends Seeder {

    public function run() {
        DB::table('users')->insert([
            [
                'first_name'     => 'Carlos',
                'last_name'      => 'Pérez Ruiz',
                'email'          => 'carlos.perez@educamadrid.com',
                'hashed_password'=> Hash::make('clave'),
                'first_log'      => false,
                'user_type'      => 'student',
                'created_at'     => Carbon::now('Europe/Madrid')
            ],
            [
                'first_name'     => 'Marcos',
                'last_name'      => 'Gómez Blanco',
                'email'          => 'marcos.gomez@educamadrid.com',
                'hashed_password'=> Hash::make('clave'),
                'first_log'      => false,
                'user_type'      => 'student',
                'created_at'     => Carbon::now('Europe/Madrid')
            ],
            [
                'first_name'     => 'Manuel',
                'last_name'      => 'Álvarez Medrano',
                'email'          => 'manuel.alvarez@educamadrid.com',
                'hashed_password'=> Hash::make('clave'),
                'first_log'      => false,
                'user_type'      => 'student',
                'created_at'     => Carbon::now('Europe/Madrid')
            ],
            [
                'first_name'     => 'Raúl',
                'last_name'      => 'Fernández Díaz',
                'email'          => 'raul.fernandez@educamadrid.com',
                'hashed_password'=> Hash::make('clave'),
                'first_log'      => false,
                'user_type'      => 'student',
                'created_at'     => Carbon::now('Europe/Madrid')
            ],
            [
                'first_name'     => 'Ariana',
                'last_name'      => 'García Manzano',
                'email'          => 'ariana.garcia@educamadrid.com',
                'hashed_password'=> Hash::make('clave'),
                'first_log'      => false,
                'user_type'      => 'teacher',
                'created_at'     => Carbon::now('Europe/Madrid')
            ],
            [
                'first_name'     => 'Miriam',
                'last_name'      => 'López Rouco',
                'email'          => 'miriam.lopezrouco@educamadrid.com',
                'hashed_password'=> Hash::make('clave'),
                'first_log'      => false,
                'user_type'      => 'teacher',
                'created_at'     => Carbon::now('Europe/Madrid')
            ],
            [
                'first_name'     => 'Marta',
                'last_name'      => 'Ramirez Castillo',
                'email'          => 'marta.ramirez@educamadrid.com',
                'hashed_password'=> Hash::make('clave'),
                'first_log'      => false,
                'user_type'      => 'teacher',
                'created_at'     => Carbon::now('Europe/Madrid')
            ],
            [
                'first_name'     => 'Lucía',
                'last_name'      => 'Fernández Soto',
                'email'          => 'lucia.fernandez@educamadrid.com',
                'hashed_password'=> Hash::make('clave'),
                'first_log'      => false,
                'user_type'      => 'admin',
                'created_at'     => Carbon::now('Europe/Madrid')
            ],
            [
                'first_name'     => 'Juan',
                'last_name'      => 'Valdés Morilla',
                'email'          => 'juan.valdes@educamadrid.com',
                'hashed_password'=> Hash::make('clave'),
                'first_log'      => false,
                'user_type'      => 'admin',
                'created_at'     => Carbon::now('Europe/Madrid')
            ],
            
        ]);
    }
}
