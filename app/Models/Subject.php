<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $primaryKey = 'subject_id';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'subject_id', 'subject_id');
    }
}
