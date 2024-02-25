<?php declare(strict_types=1);

namespace App\DTO\Tittle;

use App\Http\Requests\TittleRequest;

class TittleCreateDTO
{
    public function __construct(
        public string $tittle,
        public string $tax,
        public int $modality,
        public string $date_buy,
        public string $date_liquidity,
        public string $date_due,
    ) {
    }

    public static function DTO(TittleRequest $tittleRequest): self
    {
        return new self(
            $tittleRequest->tittle,
            $tittleRequest->tax,
            $tittleRequest->modality,
            $tittleRequest->date_buy,
            $tittleRequest->date_liquidity,
            $tittleRequest->date_due,
        );
    }
}