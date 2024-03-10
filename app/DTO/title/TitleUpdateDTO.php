<?php declare(strict_types=1);

namespace App\DTO\title;

use App\Http\Requests\TitleRequest;
use Carbon\Carbon;

class TitleUpdateDTO
{
    public function __construct(
        public string $title,
        public string $tax,
        public string $modality_id,
        public Carbon $date_buy,
        public Carbon $date_liquidity,
        public Carbon $date_due,
    ) {
    }

    public static function DTO(TitleRequest $titleRequest): self
    {
        return new self(
            $titleRequest->title,
            $titleRequest->tax,
            $titleRequest->modality_id,
            $titleRequest->date('date_buy', 'd/m/Y'),
            $titleRequest->date('date_liquidity', 'd/m/Y'),
            $titleRequest->date('date_due', 'd/m/Y'),
        );
    }

    public function toArray(): array
    {
        return [
            "title" => $this->title,
            "tax" => $this->tax,
            "modality_id" => $this->modality_id,
            "date_buy" => $this->date_buy,
            "date_liquidity" => $this->date_liquidity,
            "date_due" => $this->date_due,
        ];
    }
}