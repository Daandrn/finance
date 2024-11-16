<?php declare(strict_types=1);

namespace App\DTO\stocks;

use App\Http\Requests\UserStocksMovementRequest;
use Carbon\Carbon;

class UserStocksMovementCreateUpdateDTO
{
    public function __construct(
        public int    $user_id,
        public int    $stocks_id,
        public int    $user_stocks_id,
        public ?int   $movement_type_id = null,
        public int    $quantity,
        public string $value,
        public Carbon $date,
        public ?string $average_value,
    ) {
        $this->value = $this->toNumeric($this->value);

        if (isset($this->average_value)) $this->average_value = $this->toNumeric($this->average_value);
    }

    public static function make(UserStocksMovementRequest $request): self
    {
        return new self(
            (int) $request->user_id,
            (int) $request->stocks_id,
            (int) $request->user_stocks_id,
            $request->movement_type_id ? (int) $request->movement_type_id : $request->movement_type_id,
            (int) $request->quantity,
            $request->value,
            Carbon::parse($request->date),
            $request->average_value,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id'          => $this->user_id,
            'stocks_id'        => $this->stocks_id,
            'user_stocks_id'   => $this->user_stocks_id,
            'movement_type_id' => $this->movement_type_id,
            'quantity'         => $this->quantity,
            'value'            => $this->value,
            'date'             => $this->date,
            'average_value'    => $this->average_value,
        ];
    }

    private function toNumeric(string $value): string
    {
        return str_replace(',', '.', $value);
    }
}
