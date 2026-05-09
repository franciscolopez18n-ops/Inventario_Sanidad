<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Storage extends Model
{
    use HasFactory;

    protected $table = 'storages';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'material_id',
        'storage',
        'storage_type',
        'cabinet',
        'shelf',
        'drawer',
        'units',
        'min_units',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    public function allModifications(): HasMany
    {
        return $this->hasMany(Modification::class, 'material_id', 'material_id');
    }

    public function getExactModifications()
    {
        return Modification::where('material_id', $this->material_id)
                            ->where('storage_type', $this->storage_type)
                            ->get();
    }
}
