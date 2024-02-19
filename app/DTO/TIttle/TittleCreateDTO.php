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
    ) {}

    public static function DTO(TittleRequest $request): array
    {
        return (array) new self(
            $request->tittle,
            $request->tax,
            $request->modality,
            $request->date_buy,
            $request->date_liquidity,
            $request->date_due,
        );
    }
}