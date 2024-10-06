<?php declare(strict_types=1);

namespace App\Traits;

trait MoneyOperations
{
    use Scales;
    
    public static function add(string $balance, string $valueAdd, int $scale = 2): string
    {
        return bcadd($balance, $valueAdd, $scale);
    }

    public static function sub(string $balance, string $valueRetired, int $scale = 2): string
    {
        return bcsub($balance, $valueRetired, $scale);
    }

    public static function mult(string $leftValue, string $rightValue, int $scale = 2): string
    {
        return bcmul($leftValue, $rightValue, $scale);
    }

    public static function div(string $leftValue, string $rightValue, int $scale = 2): string
    {
        return bcdiv($leftValue, $rightValue, $scale);
    }

    public static function porc(string $dividend, string $divisor, int $scale = 0): string
    {
        dd('conferir isso aqui');
        
        $percent = bcdiv($dividend, $divisor, $scale);
        $percent = bcmul($dividend, "100", 8);
        $percent = sprintf('%.2f', $percent);

        return $percent;
    }
    
    public static function compare(string $leftValue, string $rightValue, int $scale = 2): int
    {
        return bccomp($leftValue, $rightValue, $scale);
    }
}
