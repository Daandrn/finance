<?php 

declare(strict_types=1);

namespace App\Traits;

/**
 * Scales traz uma padronização de escalas, se tratando de casas decimais, dentro da aplicação.
 * @author daandrn <dandrn7@gmail.com>
 */
trait Scales
{
    public const int DECIMALS_NO    = 0;
    public const int DECIMALS_ONE   = 1;
    public const int DECIMALS_TWO   = 2;
    public const int DECIMALS_THREE = 3;
    public const int DECIMALS_FOUR  = 4;
    public const int DECIMALS_FIVE  = 5;
    public const int DECIMALS_SIX   = 6;
    public const int DECIMALS_SEVEN = 7;
    public const int DECIMALS_EIGHT = 8;
    public const int DECIMALS_TEN   = 10;
    public const int DECIMALS_TWELVE   = 12;
    public const int DECIMALS_FOURTEEN = 14;
    public const int DECIMALS_SIXTEEN  = 16;
}
