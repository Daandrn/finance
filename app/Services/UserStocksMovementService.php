<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\stocks\{UserStocksCreateUpdateDTO, UserStocksMovementCreateUpdateDTO};
use App\Traits\{MoneyOperations, Scales};
use App\Http\Requests\UserStocksRequest;
use App\Models\{UserStocks, UserStocksMovement};
use App\Repositories\UserStocksMovementRepository;
use Exception;
use Illuminate\Support\Collection;

class UserStocksMovementService
{
    protected const int BUY = 1;
    protected const int SALE = 2;

    use MoneyOperations;
    use Scales;
    
    public function __construct(
        protected UserStocksMovementRepository $userStocksMovementRepository,
        protected UserStocksService $userStocksService,
        protected StocksServiceApi $stocksServiceApi,
    ) {
        //
    }

    public function getAll(int $user_id, ?int $stocks_id = null): Collection
    {
        $allStocksMovements = $this->userStocksMovementRepository->all($user_id, $stocks_id);
        $allStocksMovements->map(function ($movement) {
            $movement->value_total = $this->valueTotal($movement->quantity, $movement->value, 8);
        });

        return $allStocksMovements;
    }

    public function get(int $id): UserStocksMovement
    {
        $oneUserStockMovement                = $this->userStocksMovementRepository->get($id);
        $oneUserStockMovement->value_total   = $this->valueTotal($oneUserStockMovement->quantity, $oneUserStockMovement->value, 8);
        $oneUserStockMovement->current_value = $oneUserStockMovement->stocks->current_value;
        
        return $oneUserStockMovement;
    }

    public function insert(UserStocksMovementCreateUpdateDTO $dto): array
    {
        $this->userStocksMovementRepository->begin();
        
        try {
            $userStocks = $this->userStocksService->forUpdateOrCreate(
                $dto->user_id,
                $dto->stocks_id,
                UserStocksCreateUpdateDTO::make(
                    new UserStocksRequest([
                        'user_id'       => $dto->user_id,
                        'stocks_id'     => $dto->stocks_id,
                        'quantity'      => '0.00',
                        'average_value' => '0.00'
                    ])
                )
            );

            $dto->user_stocks_id = $userStocks->id;

            match ($dto->movement_type_id) {
                self::BUY  => $this->buy($userStocks, $dto->quantity, $dto->value),
                self::SALE => $this->sale($userStocks, $dto->quantity, $dto),
                default    => throw new Exception("Tipo de movimento inválido! Tipo informado: {$dto->movement_type_id}")
            };

            if (
                $dto->movement_type_id === self::SALE
                && $userStocks->quantity < 0
            ) {                
                return [
                    'body'    => null,
                    'message' => null,
                    'errors'  => "Não é possível realizar venda para este ativo. Não há quantidades disponiveis!",
                    'status'  => false
                ];
            }

            $insertedUserStocksMovement = $this->userStocksMovementRepository->insert($dto);
            $userStocks->updateOrFail();

            $this->userStocksMovementRepository->commit();

            return [
                'body'    => $insertedUserStocksMovement,
                'message' => "Movimentação incluída com sucesso!",
                'errors'  => null,
                'status'  => true
            ];
        } catch (\Throwable $error) {
            $this->userStocksMovementRepository->rollBack();

            return [
                'body'    => null,
                'message' => null,
                'errors'  => match ($error->getCode()) {
                    '23503' => "Erro ao incluir movimentação. O ativo selecionado não existe!",
                    1 => 0,
                    default => "Erro ao incluir movimentação. Verifique! " . $error->getMessage() . $error->getTraceAsString()
                },
                'status'  => false
            ];
        }
    }

    public function update(
        UserStocksMovement $userStocksMovement, 
        UserStocksMovementCreateUpdateDTO $userStocksMovementCreateUpdateDTO,
    ): UserStocksMovement {
        $updatedUserStocksMovement = $this->userStocksMovementRepository->update(
            $userStocksMovement,
            $userStocksMovementCreateUpdateDTO
        );

        return $updatedUserStocksMovement;
    }

    public function delete(int $stocks_movement_id): array
    {
        try {
            $this->userStocksMovementRepository->begin();
            $userStockMovement = $this->get($stocks_movement_id);
            $userStocks        = $this->userStocksService->forUpdate($userStockMovement->user_stocks_id);

            match ($userStockMovement->movement_type_id) {
                self::BUY => $this->revertBuy($userStockMovement, $userStocks),
                self::SALE => $this->revertSale($userStockMovement, $userStocks),
                default => throw new Exception("Tipo de movimento Inválido. Class: UserStocksMovementService. Method: delete. Valor informado: {$userStockMovement->movement_type_id}")
            };

            if ($userStocks->quantity < 0) {
                return [
                    'user_stocks_id' => $userStocks->id,
                    'error' => true,
                    'message' => "Erro ao excluir movimento. Não há quantidade suficiente. quantidade após movimentação: {$userStocks->quantity}!"
                ];
            }

            $userStocks->updateOrFail();
            $this->userStocksMovementRepository->delete($stocks_movement_id);
    
            $this->userStocksMovementRepository->commit();
            
            return [
                'user_stocks_id' => $userStocks->id,
                'error' => false
            ];
        } catch (\Throwable $error) {
            $this->userStocksMovementRepository->rollBack();
            
            return [
                'user_stocks_id' => $userStocks->id,
                'error' => true,
                'message' => 'Erro ao excluir movimento. Verifique! ' . $error->getMessage()
            ];
        }
    }

    public function buy(UserStocks $userStocks, string $quantity, string $value): void
    {
        $value_total_buy        = $this->valueTotal($quantity, $value, self::TWELVE_DECIMALS);
        $user_stock_value_total = $this->valueTotal($userStocks->quantity, $userStocks->average_value, self::TWELVE_DECIMALS);

        $userStocks->quantity   = $this->newQuantity($userStocks->quantity, $quantity, self::BUY);
        $user_stock_value_total = self::add($user_stock_value_total, $value_total_buy, self::EIGHT_DECIMALS);

        $userStocks->average_value = $this->calculateAverageValue($user_stock_value_total, $userStocks->quantity);
    }

    public function sale(UserStocks $userStocks, string $quantity, UserStocksMovementCreateUpdateDTO $dto): void
    {
        $dto->average_value  = $userStocks->average_value;
        $userStocks->quantity = $this->newQuantity($userStocks->quantity, $quantity, self::SALE);
    }

    public function revertBuy(UserStocksMovement $userStocksMovement, UserStocks $userStocks): void
    {
        $userStocks->value_total = $this->valueTotal($userStocks->quantity, $userStocks->average_value, self::EIGHT_DECIMALS);
        $userStocks->value_total = self::sub($userStocks->value_total, $userStocksMovement->value_total, self::EIGHT_DECIMALS);
        $userStocks->quantity    = $this->newQuantity($userStocks->quantity, $userStocksMovement->quantity, self::SALE);

        $userStocks->average_value = $userStocks->quantity > 0
                ? $this->calculateAverageValue($userStocks->value_total, $userStocks->quantity) 
                : $userStocks->value_total;

        unset($userStocks->value_total); //exclui value_total pois nao existe este campo no modelo
    }

    public function revertSale(UserStocksMovement $userStocksMovement, UserStocks $userStocks): void
    {
        $this->buy($userStocks, $userStocksMovement->quantity, $userStocksMovement->average_value);
    }

    public static function calculateGainPercent(string $gain, string $average_value): string
    {
        if ($average_value == '0.00') {
            return $average_value;
        }
        
        $gain_Percent = self::div($gain, $average_value, self::EIGHT_DECIMALS);
        $gain_Percent = self::mult($gain_Percent, "100", self::EIGHT_DECIMALS);
        $gain_Percent = sprintf('%.2f', $gain_Percent);
        
        return $gain_Percent;
    }

    public function valueTotal(string $quantity, string $value, ?int $decimals = self::TWELVE_DECIMALS): string
    {
        $valueTotal = self::mult($quantity, $value, $decimals);

        return sprintf('%.8f', $valueTotal);
    }

    public function newQuantity(string $leftQuantity, string $rightQuantity, int $addOrSub): string
    {
        return match ($addOrSub) {
            1 => self::add($leftQuantity, $rightQuantity, self::NO_DECIMALS),
            2 => self::sub($leftQuantity, $rightQuantity, self::NO_DECIMALS),
            default => throw new Exception("Tipo de calculo não informado! Class: UserStocksMovementService. Method: newQuantity. Valor informado: {$addOrSub}.")
        };
    }

    public function calculateAverageValue(string $value, string $quantity): string
    {
        $averageValue = self::div($value, $quantity, self::TWELVE_DECIMALS);

        return sprintf('%.8f', $averageValue);
    }
}
