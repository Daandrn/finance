<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\stocks\UserStocksMovementCreateUpdateDTO;
use App\Models\UserStocksMovement;
use App\Repositories\UserStocksMovementRepository;
use Exception;
use Illuminate\Support\Collection;

class UserStocksMovementService
{
    protected const COMPRA = 1;
    protected const VENDA = 2;
    
    public function __construct(
        protected UserStocksMovementRepository $userStocksMovementRepository,
        protected UserStocksService $userStocksService,
        protected AcoesApiService $acoesApiService,
    ) {
    }

    public function userAllStocksMovements(int $user_id, ?int $stocks_id = null): Collection
    {
        $allStockMovements = $this->userStocksMovementRepository->userAllStocksMovements($user_id, $stocks_id);
        $allStockMovements->map(function ($movement) {
            $movement->value_total = bcmul($movement->quantity, $movement->value, 2);
        });

        return $allStockMovements;
    }

    public function userOneStockMovement(int $id): UserStocksMovement
    {
        $oneUserStockMovement                = $this->userStocksMovementRepository->userOneStockMovement($id);
        $oneUserStockMovement->value_total   = bcmul($oneUserStockMovement->quantity, $oneUserStockMovement->value, 2);
        $oneUserStockMovement->current_value = $this->acoesApiService->getCurrentValue($oneUserStockMovement->ticker);
        
        return $oneUserStockMovement;
    }

    public function insert(UserStocksMovementCreateUpdateDTO $dto): UserStocksMovement
    {
        //$this->userStocksService->userOneStock($dto->);

        match ($dto->movement_type_id) {
            self::COMPRA => bcadd(),
            self::VENDA => bcsub(),
            default => throw new Exception("Tipo de movimentação inválida! Tipo informado: $dto->movement_type_id.")
        };
    
        
        dd($dto);
        
        $insertedUserStocksMovement = $this->userStocksMovementRepository->insert($dto);

        return $insertedUserStocksMovement;
    }

    public function update(UserStocksMovement $userStocksMovement, UserStocksMovementCreateUpdateDTO $userStocksMovementCreateUpdateDTO): UserStocksMovement
    {
        $updatedUserStocksMovement = $this->userStocksMovementRepository->update($userStocksMovement, $userStocksMovementCreateUpdateDTO);

        return $updatedUserStocksMovement;
    }

    public function delete(int $stocks_movement_id): void
    {
        $this->userStocksMovementRepository->delete($stocks_movement_id);

        return;
    }

    public static function calculateGain(string $value_current, string $average_value): string
    {
        return bcsub($value_current, $average_value, 2);
    }

    public static function calculateGainPercent(string $gain, string $average_value): string
    {
        $gain_Percent = bcdiv($gain, $average_value, 8);
        $gain_Percent = bcmul($gain_Percent, "100", 8);
        $gain_Percent = sprintf('%.2f', $gain_Percent);
        
        return $gain_Percent;
    }
}
