<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStocksMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stocks_id',
        'movement_type_id',
        'quantity',
        'value',
        'date',
    ];
}
