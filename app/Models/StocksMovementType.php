<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StocksMovementType extends Model
{
    public const int BUY = 1;
    public const int SALE = 2;

    use HasFactory;

    protected $fillable = [
        'description'
    ];

    public $timestamp = false;

    public function user_stocks_movements(): HasMany
    {
        return $this->hasMany(UserStocksMovement::class, 'movement_type_id', 'id');
    }
}
