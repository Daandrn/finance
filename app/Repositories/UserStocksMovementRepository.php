<?php  declare(strict_types=1);

namespace App\Repositories;

use App\DTO\stocks\UserStocksMovementCreateUpdateDTO;
use App\Models\UserStocksMovement;
use Illuminate\Database\Eloquent\Collection;

class UserStocksMovementRepository
{
    public function __construct(
        protected UserStocksMovement $userStocksMovement,
    ) {
        //
    }

    public function userAllStocksMovements(int $user_id, ?int $stocks_id = null): Collection
    {
        $userAllStocksMovements = $this->userStocksMovement
                    ->where('user_id', $user_id)
                    ->join('stocks_movement_types', 'movement_type_id', '=', 'stocks_movement_types.id', 'inner');

        $fields = [
            'user_stocks_movements.*',
            'stocks_movement_types.description',
        ];

        if (!empty($stocks_id)) {
            $userAllStocksMovements->where('stocks_id', $stocks_id)
            ->join('stocks', 'stocks_id', '=', 'stocks.id', 'inner');

            array_push($fields,
                'stocks.ticker',
                'stocks.name',
                'stocks.stocks_types_id',
            );
        }

        return $userAllStocksMovements->select($fields)->get();
    }

    public function userOneStockMovement(int $id): UserStocksMovement
    {
        $userOneStock = $this->userStocksMovement->where('user_stocks.id', $id)
                    ->join('stocks', 'stocks_id', '=', 'stocks.id', 'inner')
                    ->firstOrFail();

        return $userOneStock;
    }

    public function insert(UserStocksMovementCreateUpdateDTO $userStocksMovementCreateUpdateDTO): UserStocksMovement
    {
        $insertedUserStocks = $this->userStocksMovement->create($userStocksMovementCreateUpdateDTO->toArray());
        
        return $insertedUserStocks;
    }

    public function update(UserStocksMovement $updatedUserStocksMovement, UserStocksMovementCreateUpdateDTO $userStocksMovementCreateUpdateDTO): UserStocksMovement
    {
        $updatedUserStocksMovement->update($userStocksMovementCreateUpdateDTO->toArray());

        return $updatedUserStocksMovement;
    }

    public function delete(int $stocks_movement_id): void
    {
        $this->userStocksMovement->findOrFail($stocks_movement_id)->delete();

        return;
    }
}
