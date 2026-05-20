<?php

namespace App\Models;

use App\Contracts\StockStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageAssignment extends Model {
    use HasFactory;

    protected $table = 'storage_assignments';
    public $timestamps = false;

    protected $fillable = [
        'material_id',
        'storage',
        'storage_type',
    ];

    public function material() {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    public function storage() {
        return Storage::where('material_id', $this->material_id)->where('storage', $this->storage);
    }

    public function modifications() {
        return $this->hasMany(Modification::class, 'material_id', 'material_id')
            ->where('storage', $this->storage)
            ->where('storage_type', $this->storage_type);
    }

    public function getStorageUse() {
        return StorageUse::where('material_id', $this->material_id)
            ->where('storage', $this->storage)
            ->first();
    }

    public function getStorageReserve() {
        return StorageReserve::where('material_id', $this->material_id)
            ->where('storage', $this->storage)
            ->first();
    }

    public function storageRecord(): StockStorage {
        return $this->storage_type === 'use' 
            ? $this->getStorageUse() 
            : $this->getStorageReserve();
    }

    public function isUse(): bool {
        return $this->storage_type === 'use';
    }

    public function isReserve(): bool {
        return $this->storage_type === 'reserve';
    }
}