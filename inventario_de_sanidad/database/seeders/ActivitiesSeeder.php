<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivitiesSeeder extends Seeder {

    public function run() {
        DB::table('activities')->insert([
            ['user_id' => 3, 'title' => 'Taller de Cuidado de Heridas',          'teacher_id' => 5, 'created_at' => Carbon::now()->subDays(1)],
            ['user_id' => 3, 'title' => 'Técnicas de Esterilización',            'teacher_id' => 5, 'created_at' => Carbon::now()->subDays(2)],
            ['user_id' => 3, 'title' => 'Práctica de Suturas',                   'teacher_id' => 5, 'created_at' => Carbon::now()->subDays(3)],
            ['user_id' => 3, 'title' => 'Simulacro de Respuesta a Emergencias', 'teacher_id' => 5, 'created_at' => Carbon::now()->subDays(4)],
            ['user_id' => 3, 'title' => 'Introducción a Instrumentos Quirúrgicos','teacher_id' => 7, 'created_at' => Carbon::now()->subDays(5)],
            ['user_id' => 3, 'title' => 'Sesión de Laboratorio de Anatomía',     'teacher_id' => 7, 'created_at' => Carbon::now()->subDays(6)],
            ['user_id' => 3, 'title' => 'Conceptos Básicos de Manejo de Pacientes','teacher_id' => 7, 'created_at' => Carbon::now()->subDays(7)],
            ['user_id' => 3, 'title' => 'Control de Infecciones',                'teacher_id' => 6, 'created_at' => Carbon::now()->subDays(8)],
            ['user_id' => 3, 'title' => 'Resumen de Farmacología',               'teacher_id' => 6, 'created_at' => Carbon::now()->subDays(9)],
            ['user_id' => 3, 'title' => 'Primeros Auxilios Avanzados',           'teacher_id' => 6, 'created_at' => Carbon::now()->subDays(10)],

            ['user_id' => 4, 'title' => 'Taller de Cuidado de Heridas',          'teacher_id' => 6, 'created_at' => Carbon::now()->subDays(1)],
            ['user_id' => 4, 'title' => 'Técnicas de Esterilización',            'teacher_id' => 6, 'created_at' => Carbon::now()->subDays(2)],
            ['user_id' => 4, 'title' => 'Práctica de Suturas',                   'teacher_id' => 7, 'created_at' => Carbon::now()->subDays(3)],
            ['user_id' => 4, 'title' => 'Simulacro de Respuesta a Emergencias', 'teacher_id' => 7, 'created_at' => Carbon::now()->subDays(4)],
            ['user_id' => 4, 'title' => 'Introducción a Instrumentos Quirúrgicos','teacher_id' => 7, 'created_at' => Carbon::now()->subDays(5)],
            ['user_id' => 4, 'title' => 'Sesión de Laboratorio de Anatomía',     'teacher_id' => 5, 'created_at' => Carbon::now()->subDays(6)],
            ['user_id' => 4, 'title' => 'Conceptos Básicos de Manejo de Pacientes','teacher_id' => 5, 'created_at' => Carbon::now()->subDays(7)],
            ['user_id' => 4, 'title' => 'Control de Infecciones',                'teacher_id' => 5, 'created_at' => Carbon::now()->subDays(8)],
            ['user_id' => 4, 'title' => 'Resumen de Farmacología',               'teacher_id' => 5, 'created_at' => Carbon::now()->subDays(9)],
            ['user_id' => 4, 'title' => 'Primeros Auxilios Avanzados',           'teacher_id' => 5, 'created_at' => Carbon::now()->subDays(10)],
        ]);
    }
}
