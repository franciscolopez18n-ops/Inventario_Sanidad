<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage as StorageFacades;

class Storage extends Model {
    use HasFactory;

    protected $table = 'storages';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'material_id',
        'storage',
        'qr_path'
    ];

    public function material(): BelongsTo {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    public function storageUse() {
        return $this->hasOne(StorageUse::class, 'material_id', 'material_id')->where('storage', $this->storage);
    }

    public function storageReserve() {
        return $this->hasOne(StorageReserve::class, 'material_id', 'material_id')->where('storage', $this->storage);
    }

    public static function generateQr(int $materialId, string $storage): string {
        $directory = 'qrcodes/';
        $qrPath = $directory . $materialId . '_' . $storage . '.svg';

        if (!StorageFacades::disk('local')->exists($directory)) {
            StorageFacades::disk('local')->makeDirectory($directory);
        }

        QrCode::size(200)->generate(
            route('materials.update.qr', [
                'material' => $materialId,
                'storage'  => $storage
            ]),
            StorageFacades::disk('local')->path($qrPath)
        );

        return $qrPath;
    }
}