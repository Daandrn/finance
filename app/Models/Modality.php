<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modality extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
    ];

    public function titles(): HasMany
    {
        return $this->hasMany(Title::class);
    }
}
