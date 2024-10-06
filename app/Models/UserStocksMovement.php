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
        'average_value',
    ];

    /**
     * Get the user_stocks that owns the UserStocksMovement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_stocks(): BelongsTo
    {
        return $this->belongsTo(UserStocks::class, 'user_stocks_id', 'id');
    }

    public function users(): BelongsTo
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
