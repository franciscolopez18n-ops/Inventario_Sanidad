<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class xStorage extends Model {
    use HasFactory;

    protected $table = 'xstorages';
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
}