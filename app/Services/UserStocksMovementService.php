<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\stocks\UserStocksCreateUpdateDTO;
use App\DTO\stocks\UserStocksMovementCreateUpdateDTO;
use App\Http\Requests\UserStocksRequest;
use App\Models\UserStocks;
use App\Models\UserStocksMovement;
use App\Repositories\UserStocksMovementRepository;
use DB;
use Exception;
use Illuminate\Support\Collection;
use Request;

class UserStocksMovementService
{
    protected const BUY = 1;
    protected const SALE = 2;
    
    public function __construct(
        protected UserStocksMovementRepository $userStocksMovementRepository,
        protected UserStocksService $userStocksService,
        protected AcoesApiService $acoesApiService,
    ) {
        //
    }

    public function userAllStocksMovements(int $user_id, ?int $stocks_id = null): Collection
    {
        $allStocksMovements = $this->userStocksMovementRepository->userAllStocksMovements($user_id, $stocks_id);
        $allStocksMovements->map(function ($movement) {
            $movement->value_total = $this->valueTotal($movement->quantity, $movement->value);
        });

        return $allStocksMovements;
    }

    public function userOneStockMovement(int $id): UserStocksMovement
    {
        $oneUserStockMovement                = $this->userStocksMovementRepository->userOneStockMovement($id);
        $oneUserStockMovement->value_total   = $this->valueTotal($oneUserStockMovement->quantity, $oneUserStockMovement->value);
        $oneUserStockMovement->current_value = $this->acoesApiService->getCurrentValue($oneUserStockMovement->ticker);
        
        return $oneUserStockMovement;
    }

    public function insert(UserStocksMovementCreateUpdateDTO $dto): UserStocksMovement
    {
        DB::beginTransaction();
        
        try {
            $userStocks = $this->userStocksService->userStockForUpdateOrCreate(
                $dto->user_id,
                $dto->stocks_id,
                UserStocksCreateUpdateDTO::make(
                    new UserStocksRequest([
                        'user_id'        => $dto->user_id,
                        'stocks_id'      => $dto->stocks_id,
                        'quantity'       => '0.00',
                        'average_value'  => '0.00'
                    ])
                )
            );

            $dto->user_stocks_id = $userStocks->id;
            $insertedUserStocksMovement = $this->userStocksMovementRepository->insert($dto);
            
            match ($dto->movement_type_id) {
                self::BUY => $this->buy($userStocks, $dto->quantity, $dto->value),
                self::SALE => $this->sale($userStocks, $dto->quantity),
                default => throw new Exception("Tipo de movimento inválido! Tipo informado: {$dto->movement_type_id}")
            };
    
            $userStocks->updateOrFail();
            
            DB::commit();
        } catch (\Throwable $error) {
            DB::rollBack();

            throw new Exception("Erro ao incluir movimentação. Verifique! " . $error->getMessage());
        }

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

    public function buy(UserStocks $userStocks, string $quantity, string $value): void
    {
        $user_stock_value_total = $this->valueTotal($userStocks->quantity, $userStocks->average_value);
        $value_total_buy        = $this->valueTotal($quantity, $value);

        $userStocks->quantity   = $this->newQuantity($userStocks->quantity, $quantity, self::BUY);
        $user_stock_value_total = bcadd($user_stock_value_total, $value_total_buy);

        $userStocks->average_value = $this->calculateAverageValue($user_stock_value_total, $userStocks->quantity);
    }

    public function sale(UserStocks $userStocks, string $quantity): void
    {
        $userStocks->quantity = $this->newQuantity($userStocks->quantity, $quantity, self::SALE);
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

    public function valueTotal(string $quantity, string $value, ?int $decimals = 2): string
    {
        return bcmul($quantity, $value, $decimals);
    }

    public function newQuantity(string $leftQuantity, string $rightQuantity, int $buyOrSale): string
    {
        return match ($buyOrSale) {
            self::BUY => bcadd($leftQuantity, $rightQuantity, 0),
            self::SALE => bcsub($leftQuantity, $rightQuantity, 0)
        };
    }

    public function calculateAverageValue(string $value, string $quantity): string
    {
        $averageValue = bcdiv($value, $quantity, 2);

        return $averageValue;
    }
}
