<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialActivity extends Model
{
    use HasFactory;

    protected $table = 'material_activity';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'activity_id', 'material_id', 'units'
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'activity_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }
}
