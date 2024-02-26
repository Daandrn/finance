<?php declare(strict_types=1);

namespace App\DTO\Title;

use App\Http\Requests\TitleRequest;

class TitleCreateDTO
{
    public function __construct(
        public string $title,
        public string $tax,
        public int $modality,
        public string $date_buy,
        public string $date_liquidity,
        public string $date_due,
    ) {
    }

    public static function DTO(TitleRequest $titleRequest): self
    {
        return new self(
            $titleRequest->title,
            $titleRequest->tax,
            $titleRequest->modality,
            $titleRequest->date_buy,
            $titleRequest->date_liquidity,
            $titleRequest->date_due,
        );
    }
}