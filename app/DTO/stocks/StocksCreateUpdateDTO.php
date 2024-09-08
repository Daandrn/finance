<?php declare(strict_types=1);

namespace App\DTO\stocks;

use App\Http\Requests\StocksRequest;

class StocksCreateUpdateDTO
{
    
    public function __construct(
        public string $ticker,
        public string $name,
        public string $stocks_types_id,
    ) {
    }

    public static function make(StocksRequest $stocksRequest): self
    {
        return new self(
            $stocksRequest->ticker,
            $stocksRequest->name,
            $stocksRequest->stocks_types_id,
        );
    }

    public function toArray(): array
    {
        return [
            'ticker'          => $this->ticker,
            'name'            => $this->name,
            'stocks_types_id' => $this->stocks_types_id,
        ];
    }
}