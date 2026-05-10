<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\StockStorage;
use App\Models\Traits\HasModifications;

class StorageUse extends Model implements StockStorage {
    use HasFactory;
    use HasModifications;

    protected $table = 'storage_use';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'material_id',
        'storage',
        'units',
        'min_units',
        'cabinet',
        'shelf',
        'drawer',
    ];

    public function material() {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function getUnits(): int {
        return $this->units;
    }

    public function getMinUnits(): int {
        return $this->min_units;
    }

    public function getModeLabel(): string {
        return 'use';
    }
}