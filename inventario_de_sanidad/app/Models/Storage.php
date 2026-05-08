<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function allModifications(): HasMany {
        return $this->hasMany(Modification::class, 'material_id', 'material_id');
    }
}
