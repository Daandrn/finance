<?php 

declare(strict_types=1);

namespace App\Traits;

use InvalidArgumentException;

/**
 * Operations traz uma forma robusta de relizar operações matemáticas, principalmente no contexto de operações financeiras e/ou com casas decimais, utilizando BC Math Functions.
 * @see https://www.php.net/manual/en/ref.bc.php
 * @author daandrn <dandrn7@gmail.com>
 * @exceptionCode Parametro string não númerico: 990
 * @exceptionCode Divisor igual a 0: 991
 * @exceptionCode Escala fora do intervalo: 992
 */
trait Operations
{
    private const int ERROR_NON_NUMERIC = 990;
    private const int ERROR_DIVIDE_BY_ZERO = 991;
    private const int ERROR_INVALID_SCALE = 992;
    
    use Scales;
    
    /**
     * Soma dois valores, arredondando o resultado.
     * @param string|int $value1 valor a esquerda.
     * @param string|int $value2 valor a direita.
     * @param int $scale Numero de casas decimais após arredondamento (Padrão: 4). Para resultado sem casas decimais usar NO_DECIMALS.
     * @return string Retorna o resultado arredondado como string.
     * @throws InvalidArgumentException Caso $value1 ou $value2 não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 16.
     */
    public static function add(string|int $value1, string|int $value2, int $scale = self::DECIMALS_FOUR): string
    {
        self::validateNumeric($value1, '$value1');
        self::validateNumeric($value2, '$value2');
        self::validateScaleinterval($scale);
        
        $value = match (true) {
            self::twoValuesIsInt($value1, $value2) => strval($value1 + $value2),
            default => bcadd((string) $value1, (string) $value2, self::DECIMALS_SIXTEEN)
        };

        return sprintf("%.{$scale}f", $value);
    }

    /**
     * Realiza uma subtração, arredondando o resultado.
     * @param string|int $value1 valor a esquerda.
     * @param string|int $value2 valor a direita.
     * @param int $scale Numero de casas decimais após arredondamento (Padrão: 4). Para resultado sem casas decimais usar NO_DECIMALS.
     * @return string Retorna o resultado arredondado como string.
     * @throws InvalidArgumentException Caso $value1 ou $value2 não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 16.
     */
    public static function sub(string|int $value1, string|int $value2, int $scale = self::DECIMALS_FOUR): string
    {
        self::validateNumeric($value1, '$value1');
        self::validateNumeric($value2, '$value2');
        self::validateScaleinterval($scale);
        
        $value = match (true) {
            self::twoValuesIsInt($value1, $value2) => strval($value1 - $value2),
            default => bcsub((string) $value1, (string) $value2, self::DECIMALS_SIXTEEN)
        };

        return sprintf("%.{$scale}f", $value);
    }

    /**
     * Realiza uma multiplicação, arredondando o resultado.
     * @param string|int $value1 valor a esquerda.
     * @param string|int $value2 valor a direita.
     * @param int $scale Numero de casas decimais após arredondamento (Padrão: 4). Para resultado sem casas decimais usar NO_DECIMALS.
     * @return string Retorna o resultado arredondado como string.
     * @throws InvalidArgumentException Caso $value1 ou $value2 não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 16.
     */
    public static function mult(string|int $value1, string|int $value2, int $scale = self::DECIMALS_FOUR): string
    {
        self::validateNumeric($value1, '$value1');
        self::validateNumeric($value2, '$value2');
        self::validateScaleinterval($scale);

        $value = match (true) {
            self::twoValuesIsInt($value1, $value2) => strval($value1 * $value2),
            default => bcmul((string) $value1, (string) $value2, self::DECIMALS_SIXTEEN)
        };

        return sprintf("%.{$scale}f", $value);
    }

    /**
     * Realiza uma divisão, arredondando o resultado.
     * @param string|int $value1 valor a esquerda.
     * @param string|int $value2 valor a direita.
     * @param int $scale Numero de casas decimais após arredondamento (Padrão: 4). Para resultado sem casas decimais usar NO_DECIMALS.
     * @return string Retorna o resultado arredondado como string.
     * @throws InvalidArgumentException Caso $value1 ou $value2 não forem numericos.
     * @throws InvalidArgumentException Caso $value2 seja 0.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 16.
     */
    public static function div(string|int $value1, string|int $value2, int $scale = self::DECIMALS_FOUR): string
    {
        self::validateNumeric($value1, '$value1');
        self::validateNumeric($value2, '$value2');
        self::validateDivisor($value2);
        self::validateScaleinterval($scale);
        
        if ($value2 == 0) throw new InvalidArgumentException('Divisão por 0 é inválida.');
        
        $value = match (true) {
            self::twoValuesIsInt($value1, $value2) => strval($value1 / $value2),
            default => bcdiv((string) $value1, (string) $value2, self::DECIMALS_SIXTEEN)
        };

        return sprintf("%.{$scale}f", $value);
    }

    /**
     * Verifica a porcentagem que o valor a esquerda representa do valor a direita, arredondando o resultado.
     * @param string|int $value1 valor a esquerda.
     * @param string|int $value2 valor a direita.
     * @param int $scale Numero de casas decimais após arredondamento (Padrão: 2). Para resultado sem casas decimais usar NO_DECIMALS.
     * @return string Retorna o resultado arredondado como string.
     * @throws InvalidArgumentException Caso $value1 ou $value2 não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 16.
     */
    public static function percentual(string|int $value1, string|int $value2, int $scale = self::DECIMALS_TWO): string
    {
        self::validateNumeric($value1, '$value1');
        self::validateNumeric($value2, '$value2');
        self::validateDivisor($value2);
        self::validateScaleinterval($scale);

        $value = match (true) {
            self::twoValuesIsInt($value1, $value2) => strval($value1 / $value2),
            default => bcdiv((string) $value1, (string) $value2, self::DECIMALS_SIXTEEN)
        };

        $value = bcmul($value, "100", self::DECIMALS_SIXTEEN);

        return sprintf("%.{$scale}f", $value);
    }
    
    /**
     * Compara dois valores.
     * @param $value1 valor a esquerda.
     * @param $value2 valor a direita.
     * @param $scale Numero de casas decimais que será usada na comparação (Padrão: 6). Para resultado sem casas decimais usar NO_DECIMALS.
     * @return int 0 se os valores forem iguais, 1 se $value1 maior que $value2, -1 caso contrário.
     * @throws InvalidArgumentException Caso $value1 ou $value2 não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 16.
     */
    public static function compare(string|int $value1, string|int $value2, int $scale = self::DECIMALS_SIXTEEN): int
    {
        self::validateNumeric($value1, '$value1');
        self::validateNumeric($value2, '$value2');
        self::validScaleinterval($scale);
        
        return bccomp($value1, $value2, $scale);
    }

    /**
     * Verifica se os valores passados como string são numéricos.
     * @param string|int $paramValue Recebe os valores dos parametros $value1 ou $value2.
     * @param string|int $ParamName Recebe o nome do parametro para ser exibido na exceção.
     * @throws InvalidArgumentException
     * @return InvalidArgumentException
     */
    private static function validateNumeric(string|int $paramValue, string $ParamName): void
    {
        if (!is_numeric($paramValue)) 
        throw new InvalidArgumentException("Valor inválido para $ParamName.", self::ERROR_NON_NUMERIC);
    }

    /**
     * Verifica se o valor a direita é 0, pois divisão por 0 é inválida.
     * @param int $paramValue2 Recebe o valor do parametro $value2.
     * @throws InvalidArgumentException
     * @return InvalidArgumentException
     */
    private static function validateDivisor(string|int $paramValue2): void
    {
        if ($paramValue2 == 0) 
        throw new InvalidArgumentException('Divisão por 0 é inválida.', self::ERROR_DIVIDE_BY_ZERO);
    }

    /**
     * Verifica se o numero de casas decimais é válido.
     * @param int $paramScale Recebe o valor do parametro $scale.
     * @throws InvalidArgumentException
     * @return InvalidArgumentException
     */
    private static function validateScaleinterval(int $paramScale): void
    {
        if ($paramScale < 0 || $paramScale > 16) 
        throw new InvalidArgumentException('Valor inválido para $scale. Use de 0 a 16.', self::ERROR_INVALID_SCALE);
    }

    /**
     * Verifica se ambos os parametros são inteiros.
     * @param string|int $paramValue1 Recebe o parametro $value1.
     * @param string|int $paramValue2 Recebe o parametro $value2.
     * @return bool
     */
    private static function twoValuesIsInt(string|int $paramValue1, string|int $paramValue2): bool
    {
        return is_int($paramValue1) && is_int($paramValue2);
    }
}
