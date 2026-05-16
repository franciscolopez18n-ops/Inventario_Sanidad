<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\xStorage;
use Illuminate\Support\Facades\Storage as StorageFacades;

class xStoragesSeeder extends Seeder {

    // Combinaciones material_id => [almacenes]
    private array $storages = [
        1  => ['odontology', 'CAE'],
        2  => ['CAE'],
        3  => ['odontology'],
        4  => ['CAE'],
        5  => ['CAE'],
        6  => ['CAE'],
        7  => ['odontology', 'CAE'],
        8  => ['CAE'],
        9  => ['CAE'],
        10 => ['CAE'],
        11 => ['CAE'],
        12 => ['CAE'],
        13 => ['CAE'],
        14 => ['CAE'],
        15 => ['CAE'],
        16 => ['CAE'],
        17 => ['CAE'],
        18 => ['CAE'],
        19 => ['CAE'],
        20 => ['CAE'],
        21 => ['CAE'],
        22 => ['CAE'],
        23 => ['CAE'],
        24 => ['CAE'],
        25 => ['CAE'],
        26 => ['CAE'],
        27 => ['CAE'],
        28 => ['CAE'],
    ];

    public function run() {
        StorageFacades::disk('local')->deleteDirectory('qrcodes');
        StorageFacades::disk('local')->makeDirectory('qrcodes');

        foreach ($this->storages as $materialId => $locations) {
            foreach ($locations as $location) {
                xStorage::create([
                    'material_id' => $materialId,
                    'storage'     => $location,
                    'qr_path'     => xStorage::generateQr($materialId, $location),
                ]);
            }
        }
    }
}