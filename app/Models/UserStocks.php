<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserStocks extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stocks_id',
        'quantity',
        'average_value',
    ];

    public function stocks(): BelongsTo
    {
        return $this->belongsTo(Stocks::class, 'stocks_id', 'id');
    }
}
