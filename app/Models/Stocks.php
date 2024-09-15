<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stocks extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticker',
        'name',
        'stocks_types_id',
    ];
    
    public $timestamps = false;

    public function setTickerAttribute($value): void
    {
        $this->attributes['ticker'] = strtoupper($value);
    }

    public function user_stocks_movement(): HasMany
    {
        return $this->hasMany(UserStocksMovement::class, 'stocks_id', 'id');
    }

    /**
     * Get all of the user_stocks for the Stocks
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_stocks(): HasMany
    {
        return $this->hasMany(UserStocks::class, 'stocks_id', 'id');
    }

    /**
     * Get the stocks_types that owns the Stocks
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stocks_types(): BelongsTo
    {
        return $this->belongsTo(StocksType::class, 'stocks_types_id', 'id')->withDefault();
    }
}
