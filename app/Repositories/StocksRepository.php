<?php  declare(strict_types=1);

namespace App\Repositories;

use App\DTO\stocks\StocksCreateUpdateDTO;
use App\Models\Stocks;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

class StocksRepository
{
    public function __construct(
        protected Stocks $stocks,
    ) {
    }

    public function all(string $direction = 'asc'): Collection
    {
        $stocks = $this->stocks->orderBy('id', $direction)->get();
        
        return $stocks;
    }

    public function paginate(int $perPage): Paginator
    {
        return $this->stocks
                ->orderBy('stocks.id')
                ->with('stocks_types')
                ->simplePaginate($perPage);
    }

    public function getOne(string $id): Model|null
    {
        $oneStocks = $this->stocks->findOrFail($id);

        return $oneStocks;
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

    public function deleteOne(string $id): void
    {
        $stocksDeleted = $this->stocks->findOrFail($id);
        $stocksDeleted->delete();

        return;
    }
}
