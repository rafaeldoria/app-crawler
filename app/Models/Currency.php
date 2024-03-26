<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'number',
        'decimal',
        'currency'
    ];

    public function location(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}