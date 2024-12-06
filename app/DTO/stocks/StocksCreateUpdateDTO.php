<?php declare(strict_types=1);

namespace App\DTO\stocks;

use App\Http\Requests\StocksRequest;

class StocksCreateUpdateDTO
{
    
    public function __construct(
        public string $ticker,
        public string $name,
        public int    $stocks_types_id,
        public bool   $status,
    ) {
        //
    }

    public static function make(StocksRequest $request): self
    {
        return new self(
            $request->ticker,
            $request->name,
            $request->stocks_types_id,
            $request->status
        );
    }

    public function toArray(): array
    {
        return [
            'ticker'          => $this->ticker,
            'name'            => $this->name,
            'stocks_types_id' => $this->stocks_types_id,
            'status'          => $this->status,
        ];
    }
}
