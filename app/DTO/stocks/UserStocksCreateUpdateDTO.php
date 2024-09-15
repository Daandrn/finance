<?php declare(strict_types=1);

namespace App\DTO\stocks;

use App\Http\Requests\UserStocksRequest;

class UserStocksCreateUpdateDTO
{
    public function __construct(
        public int    $user_id,
        public int    $stocks_id,
        public string $quantity,
        public string $average_value,
    ) {
        $this->quantity = $this->toNumeric($this->quantity);
    }

    public static function make(UserStocksRequest $request): self
    {
        return new self(
            (int) $request->user_id,
            (int) $request->stocks_id,
            $request->quantity,
            $request->average_value,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id'       => $this->user_id,
            'stocks_id'     => $this->stocks_id,
            'quantity'      => $this->quantity,
            'average_value' => $this->average_value,
        ];
    }

    private function toNumeric(string $value): string
    {
        return str_replace(',', '.', $value);
    }
}
