<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{
    use HasFactory;
    protected $table = 'modifications';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;
    protected $casts = [
        'action_datetime' => 'datetime',
    ];
    protected $fillable = [
        'user_id', 'material_id', 'storage_type', 'storage', 'action_datetime', 'units',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class, ['material_id', 'storage_type']);
    }
}
