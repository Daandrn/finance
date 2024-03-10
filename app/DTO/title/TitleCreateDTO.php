<?php declare(strict_types=1);

namespace App\DTO\title;

use App\Http\Requests\TitleRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TitleCreateDTO
{
    public function __construct(
        public int $user_id,
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
            Auth::user()->id,
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
            'user_id' => $this->user_id,
            'title' => $this->title,
            'tax' => $this->tax,
            'modality_id' => $this->modality_id,
            'date_buy' => $this->date_buy,
            'date_liquidity' => $this->date_liquidity,
            'date_due' => $this->date_due,
        ];
    }
}