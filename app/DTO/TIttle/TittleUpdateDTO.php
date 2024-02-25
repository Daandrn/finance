<?php declare(strict_types=1);

namespace App\DTO\Tittle;

use App\Http\Requests\TittleRequest;
use stdClass;

class TittleUpdateDTO
{
    public function __construct(
        public string $id,
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
            $tittleRequest->id,
            $tittleRequest->tittle,
            $tittleRequest->tax,
            $tittleRequest->modality,
            $tittleRequest->date_buy,
            $tittleRequest->date_liquidity,
            $tittleRequest->date_due,
        );
    }
}