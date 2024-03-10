<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'tax',
        'modality_id',
        'date_buy',
        'date_liquidity',
        'date_due',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
    
    public function modality(): BelongsTo
    {
        return $this->belongsTo(Modality::class)->withDefault();
    }
}