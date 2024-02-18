<?php declare(strict_types=1);

namespace App\Functions;

define("TWODECIMALS", 2);

class Money
{
    public static function add(string $balance, string $valueAdd): string
    {
        return bcadd($balance, $valueAdd, TWODECIMALS);
    }

    public static function sub(string $balance, string $valueRetired): string
    {
        return bcsub($balance, $valueRetired, TWODECIMALS);
    }

    public static function mult(string $leftValue, string $rightValue): string
    {
        return bcmul($leftValue, $rightValue, TWODECIMALS);
    }

    public static function porc(string $dividend, string $divisor): string
    {
        return bcdiv($dividend, $divisor, TWODECIMALS);
    }
    
    public static function compare(string $leftValue, string $rightValue): int
    {
        return bccomp($leftValue, $rightValue, TWODECIMALS);
    }
}