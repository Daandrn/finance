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
        $userAllStocks = $this->userStocks
                    ->with('stocks')
                    ->where('user_id', $user_id)
                    ->get();
                    
        return $userAllStocks;
    }

    public function userStockForUpdateOrCreate(int $user_id, int $stocks_id, UserStocksCreateUpdateDTO $dto): UserStocks
    {
        $userStockForUpdate = $this->userStocks
                    ->lockForUpdate()
                    ->firstOrCreate([
                        'user_id'   => $user_id,
                        'stocks_id' => $stocks_id,
                    ], [
                        'quantity' => $dto->quantity,
                        'average_value' => $dto->average_value,
                    ]);

        return $userStockForUpdate;
    }

    public function userOneStock(int $user_stock_id): UserStocks
    {
        $userOneStock = $this->userStocks
                    ->with('stocks')
                    ->where('id', $user_stock_id)
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
