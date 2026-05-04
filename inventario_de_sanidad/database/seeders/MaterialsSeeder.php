<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialsSeeder extends Seeder {

    public function run() {
        DB::table('materials')->insert([
            ['material_id' => 1, 'name' => 'PEAN', 'description' => 'Pinza de sujeción', 'image_path' => 'materials/pean.jpg'],
            ['material_id' => 2, 'name' => 'Tijeras', 'description' => 'Tijeras quirúrgicas', 'image_path' => 'materials/tijeras.jpg'],
            ['material_id' => 3, 'name' => 'Tijeras Vendaje', 'description' => 'Tijeras para corte de vendajes', 'image_path' => 'materials/tijeras_vendaje.jpg'],
            ['material_id' => 4, 'name' => 'Pinzas de Disección c/ y s/ Dientes', 'description' => 'Pinzas de disección con y sin dientes', 'image_path' => null],
            ['material_id' => 5, 'name' => 'Estelites', 'description' => 'Material utilizado en cirugía dental', 'image_path' => 'materials/estelites.jpg'],
            ['material_id' => 6, 'name' => 'Kocher Rectas/Curvas', 'description' => 'Pinzas Kocher rectas y curvas', 'image_path' => 'materials/kocher_rectas_curvas.jpg'],
            ['material_id' => 7, 'name' => 'Kocher Plástico', 'description' => 'Pinzas Kocher de plástico', 'image_path' => 'materials/kocher_plastico.jpg'],
            ['material_id' => 8, 'name' => 'Sonda Acanalada', 'description' => 'Sonda con canalización', 'image_path' => 'materials/sonda_acanalada.jpg'],
            ['material_id' => 9, 'name' => 'Porta Agujas', 'description' => 'Porta agujas quirúrgicas', 'image_path' => 'materials/porta_agujas.jpg'],
            ['material_id' => 10, 'name' => 'Mangos Bisturí nº4', 'description' => 'Mangos para bisturí número 4', 'image_path' => 'materials/mangos_bisturi_n4.jpg'],
            ['material_id' => 11, 'name' => 'Bisturí Desechable', 'description' => 'Bisturí desechable', 'image_path' => 'materials/bisturi_desechable.jpg'],
            ['material_id' => 12, 'name' => 'Quitagrapas', 'description' => 'Instrumento para retirar grapas', 'image_path' => 'materials/quitagrapas.jpg'],
            ['material_id' => 13, 'name' => 'Bisturí Eléctrico', 'description' => 'Bisturí eléctrico', 'image_path' => 'materials/bisturi_electrico.jpg'],
            ['material_id' => 14, 'name' => 'Tapones Sonda (Caja)', 'description' => 'Tapones para sonda (caja)', 'image_path' => 'materials/tapones_sonda_caja.jpg'],
            ['material_id' => 15, 'name' => 'Tapones Vía (Caja)', 'description' => 'Tapones para vía (caja)', 'image_path' => null],
            ['material_id' => 16, 'name' => 'Llaves 3 Vías c/ y s/ Alargadera', 'description' => 'Llaves de 3 vías con y sin alargadera', 'image_path' => 'materials/llaves_3_vias_c_y_s_alargadera.jpg'],
            ['material_id' => 17, 'name' => 'Cánulas Traqueostomía', 'description' => 'Cánulas para traqueostomía', 'image_path' => 'materials/canulas_traqueostomia.jpg'],
            ['material_id' => 18, 'name' => 'Tracción Adhesiva a Piel', 'description' => 'Sistema de tracción adhesiva para la piel', 'image_path' => 'materials/traccion_adhesiva_a_piel.jpg'],
            ['material_id' => 19, 'name' => 'Bolsas Colostomía Abierta 1 Pieza (Caja)', 'description' => 'Bolsas para colostomía abierta (1 pieza)', 'image_path' => null],
            ['material_id' => 20, 'name' => 'Bolsas Colostomía Cerrada 1 Pieza (Caja)', 'description' => 'Bolsas para colostomía cerrada (1 pieza)', 'image_path' => null],
            ['material_id' => 21, 'name' => 'Bolsas Colostomía Cerrada 2 Piezas (Caja)', 'description' => 'Bolsas para colostomía cerrada (2 piezas)', 'image_path' => null],
            ['material_id' => 22, 'name' => 'Apósitos Hidropolimérico Varias Formas', 'description' => 'Apósitos hidropoliméricos en varias formas', 'image_path' => 'materials/apositos_hidropolimerico_varias_formas.jpg'],
            ['material_id' => 23, 'name' => 'Esparadrapo 2,5 cm Papel', 'description' => 'Esparadrapo de papel (2.5 cm)', 'image_path' => null],
            ['material_id' => 24, 'name' => 'Esparadrapo 2,5 cm Microperforado Transparente', 'description' => 'Esparadrapo microperforado transparente (2.5 cm)', 'image_path' => 'materials/esparadrapo_2_5_cm_microperforado_transparente.jpg'],
            ['material_id' => 25, 'name' => 'Apósitos Transparente Vías (Caja)', 'description' => 'Apósitos transparentes para vías (caja)', 'image_path' => 'materials/apositos_transparente_vias_caja.jpg'],
            ['material_id' => 26, 'name' => 'Mepore Varios Tamaños (Caja)', 'description' => 'Apósitos Mepore de varios tamaños (caja)', 'image_path' => null],
            ['material_id' => 27, 'name' => 'Apósito Aquacel Ag+', 'description' => 'Apósito Aquacel Ag+', 'image_path' => 'materials/aposito_aquacel_ag_plus.jpg'],
            ['material_id' => 28, 'name' => 'Linitul (Caja)', 'description' => 'Linitul (caja)', 'image_path' => 'materials/linitul_caja.jpg'],
        ]);
    }
}
