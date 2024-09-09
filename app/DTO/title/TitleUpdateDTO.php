<?php declare(strict_types=1);

namespace App\DTO\title;

use App\Http\Requests\TitleRequest;
use Carbon\Carbon;

class TitleUpdateDTO
{
    public function __construct(
        public string  $title,
        public int     $title_type_id,
        public int     $modality_id,
        public ?string $tax,
        public Carbon  $date_buy,
        public Carbon  $date_liquidity,
        public Carbon  $date_due,
        public string  $value_buy,
        public string  $value_current,
    ) {
        $this->value_buy     = $this->toNumeric($this->value_buy);
        $this->value_current = $this->toNumeric($this->value_current);
        $this->tax           = $this->tax ? $this->toNumeric($this->tax) : $this->setValTax($this->modality_id);
    }

    public static function make(TitleRequest $titleRequest): self
    {
        return new self(
            $titleRequest->title,
            (int) $titleRequest->title_type_id,
            (int) $titleRequest->modality_id,
            $titleRequest->tax,
            $titleRequest->date('date_buy', 'Y-m-d'),
            $titleRequest->date('date_liquidity', 'Y-m-d'),
            $titleRequest->date('date_due', 'Y-m-d'),
            $titleRequest->value_buy,
            $titleRequest->value_current,
        );
    }

    private function toNumeric(string $value): string
    {
        return str_replace(',', '.', $value);
    }

    private function setValTax(int $modality_id): string
    {   
        $tax = match (true) {
            $modality_id == 4 => "4.74",
            $modality_id == 6 => "11.75",
        };
        
        return $tax;
    }

    public function toArray(): array
    {
        return [
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