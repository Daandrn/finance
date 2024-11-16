<?php declare(strict_types=1);

namespace Tests;

use App\Models\Stocks;
use App\Models\User;
use App\Traits\Scales;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class Teste
{
    public function __construct() 
    {
        //
    }
    
    public function index()
    {
        //
    }
}

function div(string|int $value1, string|int $value2, int $scale = 4): string
{
    if (!is_numeric($value1)) throw new InvalidArgumentException('Valor inválido para $value1');
    if (!is_numeric($value2)) throw new InvalidArgumentException('Valor inválido para $value2');
    if ($value2 == 0) throw new InvalidArgumentException('Divisão por 0 é inválida.');
    if ($scale < 0 || $scale > 16) throw new InvalidArgumentException('Intervalo inválido para $scale. Use de 0 a 16.');
    
    $value = match (true) {
        is_int($value1) && is_int($value2) => (string) ($value1 / $value2),
        default => bcdiv((string) $value1, (string) $value2, 16)
    };

    return sprintf("%.{$scale}f", $value);
}

dd(
    div(-1, '-4')
);
