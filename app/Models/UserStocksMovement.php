<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStocksMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stocks_id',
        'user_stocks_id',
        'movement_type_id',
        'quantity',
        'value',
        'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    public function stocks(): BelongsTo
    {
        return $this->belongsTo(Stocks::class, 'stocks_id', 'id')->withDefault();
    }

    public function stocks_movement_types(): BelongsTo
    {
        return $this->belongsTo(StocksMovementType::class, 'movement_type_id', 'id')->withDefault();
    }
}
