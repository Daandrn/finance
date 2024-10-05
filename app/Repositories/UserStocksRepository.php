<?php  declare(strict_types=1);

namespace App\Repositories;

use App\DTO\stocks\UserStocksCreateUpdateDTO;
use App\Models\UserStocks;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class UserStocksRepository
{
    public function __construct(
        protected UserStocks $userStocks,
    ) {
        //
    }

    /**
     * @param int $user_id
     * @return Collection
     */
    public function all(int $user_id): Collection
    {
        $userAllStocks = $this->userStocks
                    ->with([
                        'stocks' => function ($query) {
                            $query->orderBy('ticker', 'asc');
                    }])
                    ->where('quantity', '>', '0.00')
                    ->get();

        return $userAllStocks;
    }

    public function forUpdateOrCreate(int $user_id, int $stocks_id, UserStocksCreateUpdateDTO $dto): UserStocks
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

    public function forUpdate(int $user_stocks_id): UserStocks
    {
        $userStockForUpdate = $this->userStocks
                    ->where('id', '=', $user_stocks_id)
                    ->lockForUpdate()
                    ->firstOrFail();

        return $userStockForUpdate;
    }

    public function get(int $user_stocks_id): UserStocks
    {
        $userOneStocks = $this->userStocks
                    ->with('stocks')
                    ->where('id', '=', $user_stocks_id)
                    ->firstOrFail();
        
        return $userOneStocks;
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

    public function delete(int $user_stocks_id): void
    {
        $this->userStocks->destroy($user_stocks_id);

        return;
    }
}
