<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'icon',
        'currency_id',
    ];

    public function currency() : BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
