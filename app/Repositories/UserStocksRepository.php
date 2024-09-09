<?php  declare(strict_types=1);

namespace App\Repositories;

use App\DTO\stocks\UserStocksCreateUpdateDTO;
use App\Models\UserStocks;
use Illuminate\Database\Eloquent\Collection;

class UserStocksRepository
{
    public function __construct(
        protected UserStocks $userStocks,
    ) {
        //
    }

    public function userAllStocks(int $user_id): Collection
    {
        $userAllStocks = $this->userStocks->where('user_id', $user_id)
                    ->join('stocks', 'stocks_id', '=', 'stocks.id', 'inner')
                    ->get();

        return $userAllStocks;
    }

    public function userStockForUpdate(int $id): UserStocks
    {
        $userStockForUpdate = $this->userStocks->find($id)->lockForUpdate();

        dd($userStockForUpdate);
        
        return $userStockForUpdate;
    }

    public function userOneStock(int $id): UserStocks
    {
        $userOneStock = $this->userStocks->where('user_stocks.id', $id)
                    ->join('stocks', 'stocks_id', '=', 'stocks.id', 'inner')
                    ->firstOrFail();
        
        return $userOneStock;
    }

    public function insert(UserStocksCreateUpdateDTO $userStocksCreateUpdateDTO): UserStocks
    {
        $insertedUserStocks = $this->userStocks->create($userStocksCreateUpdateDTO->toArray());
        
        return $insertedUserStocks;
    }

    public function update(UserStocks $updatedUserStocks, UserStocksCreateUpdateDTO $userStocksCreateUpdateDTO): UserStocks
    {
        $updatedUserStocks->update($userStocksCreateUpdateDTO->toArray());

        return $updatedUserStocks;
    }

    public function delete(int $userStocks_id): void
    {
        $this->userStocks->destroy($userStocks_id);

        return;
    }
}
