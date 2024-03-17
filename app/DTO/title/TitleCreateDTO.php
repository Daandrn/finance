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
        public string $title_type_id,
        public string $modality_id,
        public string $tax,
        public Carbon $date_buy,
        public Carbon $date_liquidity,
        public Carbon $date_due,
        public string $value_buy,
        public string $value_current,
    ) {
        $this->value_buy     = self::toValue($this->value_buy);
        $this->value_current = self::toValue($this->value_current);
    }

    public static function DTO(TitleRequest $titleRequest): self
    {
        return new self(
            Auth::user()->id,
            $titleRequest->title,
            $titleRequest->title_type_id,
            $titleRequest->modality_id,
            $titleRequest->tax,
            $titleRequest->date('date_buy', 'd/m/Y'),
            $titleRequest->date('date_liquidity', 'd/m/Y'),
            $titleRequest->date('date_due', 'd/m/Y'),
            $titleRequest->value_buy,
            $titleRequest->value_current,
        );
    }

    private static function toValue(string $value): string
    {
        $value = preg_replace('/\./', '', $value);
        $value = preg_replace('/\,/', '.', $value);
        
        return $value;
    }

    public function toArray(): array
    {
        return [
            'user_id'        => $this->user_id,
            'title'          => $this->title,
            'title_type_id'  => $this->title_type_id,
            'modality_id'    => $this->modality_id,
            'tax'            => $this->tax,
            'date_buy'       => $this->date_buy,
            'date_liquidity' => $this->date_liquidity,
            'date_due'       => $this->date_due,
            'value_buy'      => $this->value_buy,
            'value_current'  => $this->value_current,
        ];
    }
}