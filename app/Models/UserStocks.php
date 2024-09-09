<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStocks extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stocks_id',
        'quantity',
    ];
}
