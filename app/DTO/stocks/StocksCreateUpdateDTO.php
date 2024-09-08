<?php declare(strict_types=1);

namespace App\DTO\stocks;

use App\Http\Requests\StocksRequest;

class StocksCreateUpdateDTO
{
    
    public function __construct(
        public string $ticker,
        public string $name,
    ) {
    }

    public static function make(StocksRequest $stocksRequest): self
    {
        return new self(
            $stocksRequest->ticker,
            $stocksRequest->name,
        );
    }

    public function toArray(): array
    {
        return [
            'ticker' => $this->ticker,
            'name'   => $this->name,
        ];
    }
}