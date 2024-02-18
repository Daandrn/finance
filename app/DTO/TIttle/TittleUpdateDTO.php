<?php 

namespace App\DTO\Tittle;

use App\Http\Requests\TittleRequest;

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
    ) {}

    public static function DTO(TittleRequest $request): array
    {
        return (array) new self(
            $id ?? $request->id,
            $request->tittle,
            $request->tax,
            $request->modality,
            $request->date_buy,
            $request->date_liquidity,
            $request->date_due,
        );
    }
}