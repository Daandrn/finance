<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TitleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'has_irpf',
    ];

    public function titles(): HasMany
    {
        return $this->hasMany(Title::class, 'title_type_id', 'id');
    }
}
