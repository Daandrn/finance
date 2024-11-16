<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Split extends Model
{
    use HasFactory;

    protected $fillable = [
        'stocks_id',
        'date',
        'grouping',
        'split',
    ];

    protected $timestamp = true;

    /**
     * Get the stocks that owns the Split
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stocks(): BelongsTo
    {
        return $this->belongsTo(Stocks::class, 'stocks_id', 'id');
    }
}
