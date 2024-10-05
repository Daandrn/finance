<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserStocks extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stocks_id',
        'quantity',
        'average_value',
    ];

    /**
     * Get all of the user_stocks_movements for the UserStocks
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_stocks_movements(): HasMany
    {
        return $this->hasMany(UserStocksMovement::class, 'user_stocks_id', 'id');
    }

    public function stocks(): BelongsTo
    {
        return $this->belongsTo(Stocks::class, 'stocks_id', 'id');
    }
}
