<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model {
    use HasFactory;

    protected $table = 'materials';
    public $timestamps = false;
    protected $primaryKey = 'material_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'name', 'description', 'image_path',
    ];

    public function storage() {
        return $this->hasMany(Storage::class, 'material_id', 'material_id');
    }

    public function storageUse() {
        return $this->hasMany(StorageUse::class, 'material_id');
    }

    public function storageReserve() {
        return $this->hasMany(StorageReserve::class, 'material_id');
    }

    public function modifications() {
        return $this->hasMany(Modification::class, 'material_id', 'material_id');
    }

    public function activities() {
        return $this->belongsToMany(Activity::class, 'material_activity', 'material_id', 'activity_id')
                    ->withPivot('quantity');
    }
}
