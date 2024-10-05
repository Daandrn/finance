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

    public function all(int $user_id, ?int $stocks_id = null): Collection
    {
        $userAllStocksMovements = $this->userStocksMovement
                    ->with('stocks_movement_types')
                    ->where('user_id', $user_id);

        if (!empty($stocks_id)) {
            $userAllStocksMovements->where('stocks_id', $stocks_id);
        }

        return $userAllStocksMovements->get();
    }

    public function get(int $id): UserStocksMovement
    {
        $userStocks = $this->userStocksMovement
                    ->with('stocks')
                    ->where('id', $id)
                    ->firstOrFail();

        return $userStocks;
    }

    public function insert(UserStocksMovementCreateUpdateDTO $userStocksMovementCreateUpdateDTO): UserStocksMovement
    {
        $insertedUserStocks = $this->userStocksMovement->create(
            $userStocksMovementCreateUpdateDTO->toArray()
        );
        
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
