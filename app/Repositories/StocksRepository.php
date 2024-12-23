<?php  declare(strict_types=1);

namespace App\Repositories;

use App\DTO\stocks\StocksCreateUpdateDTO;
use App\Models\Stocks;
use App\Traits\TransactionStatments;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\{Model, Collection};

class StocksRepository
{
    use TransactionStatments;

    public function __construct(
        protected Stocks $stocks,
    ) {
        //
    }

    public function all(string $direction = 'asc'): Collection
    {
        $stocks = $this->stocks->orderBy('id', $direction)->get();
        
        return $stocks;
    }

    public function paginate(int $perPage): LengthAwarePaginator 
    {
        return $this->stocks
                ->orderBy('stocks.id')
                ->with('stocks_types')
                ->Paginate($perPage);
    }

    public function get(int $id): Stocks|null
    {
        $stocks = $this->stocks->findOrFail($id);

        return $stocks;
    }

    public function new(stocksCreateUpdateDTO $stocksCreateUpdateDTO): stocks
    {
        $newStocks = $this->stocks->create($stocksCreateUpdateDTO->toArray());

        return $newStocks;
    }

    public function update(int $id, stocksCreateUpdateDTO $stocksCreateUpdateDTO): bool
    {
        $updatedStocks = $this->stocks->findOrFail($id);

        return $updatedStocks->updateOrFail($stocksCreateUpdateDTO->toArray());;
    }

    public function delete(Stocks $stocks): void
    {
        $stocks->delete();

        return;
    }
}
