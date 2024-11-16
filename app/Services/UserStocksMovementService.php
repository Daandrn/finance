<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\stocks\{UserStocksCreateUpdateDTO, UserStocksMovementCreateUpdateDTO};
use App\Traits\{Operations, Scales};
use App\Http\Requests\UserStocksRequest;
use App\Models\{UserStocks, UserStocksMovement};
use App\Repositories\UserStocksMovementRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Exception;

class UserStocksMovementService
{
    protected const int BUY = 1;
    protected const int SALE = 2;

    use Operations;
    use Scales;
    
    public function __construct(
        protected UserStocksMovementRepository $repository,
        protected UserStocksService $userStocksService,
        protected StocksServiceApi $stocksServiceApi,
    ) {
        //
    }

    public function getAll(int $user_id, ?int $stocks_id = null): Collection
    {
        $all = $this->repository->all($user_id, $stocks_id);
        $all->map(function ($movement) {
            $movement->value_total = $this->valueTotal((string) $movement->quantity, $movement->value, 8);
        });

        $all = $all->sortByDesc(function ($item) {
            return Carbon::parse($item->date);
        });

        return $all;
    }

    public function get(int $id): UserStocksMovement
    {
        $stocksMovement                = $this->repository->get($id);
        $stocksMovement->value_total   = $this->valueTotal((string) $stocksMovement->quantity, $stocksMovement->value, 8);
        $stocksMovement->current_value = $stocksMovement->stocks->current_value;
        
        return $stocksMovement;
    }

    public function insert(UserStocksMovementCreateUpdateDTO $dto): array
    {
        $this->repository->begin();
        
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
                self::BUY  => $this->buy($userStocks, (string) $dto->quantity, $dto->value),
                self::SALE => $this->sale($userStocks, (string) $dto->quantity, $dto),
                default    => throw new Exception("Tipo de movimento inválido! Tipo informado: {$dto->movement_type_id}")
            };
            
            if (
                $dto->movement_type_id === self::SALE
                && $userStocks->quantity < 1
            ) {
                return [
                    'body'    => null,
                    'message' => null,
                    'errors'  => "Não é possível realizar venda para este ativo. Não há quantidades disponiveis!",
                    'status'  => false
                ];
            }

            $insertedUserStocksMovement = $this->repository->insert($dto);
            $userStocks->updateOrFail();

            $this->repository->commit();

            return [
                'body'    => $insertedUserStocksMovement,
                'message' => "Movimentação incluída com sucesso!",
                'errors'  => null,
                'status'  => true
            ];
        } catch (\Throwable $error) {
            $this->repository->rollBack();

            return [
                'body'    => null,
                'message' => null,
                'errors'  => match ($error->getCode()) {
                    '23503' => "Erro ao incluir movimentação. O ativo selecionado não existe!",
                    1 => 0,
                    default => "Erro ao incluir movimentação. Verifique! " . $error->getMessage()
                },
                'status'  => false
            ];
        }
    }

    public function insertByImport(UserStocksMovementCreateUpdateDTO $dto)
    {
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
            self::BUY  => $this->buy($userStocks, (string) $dto->quantity, $dto->value),
            self::SALE => $this->sale($userStocks, (string) $dto->quantity, $dto)
        };

        $this->repository->insert($dto);
        $userStocks->updateOrFail();

        return;
    }

    public function update(
        UserStocksMovement $userStocksMovement, 
        UserStocksMovementCreateUpdateDTO $userStocksMovementCreateUpdateDTO,
    ): UserStocksMovement {
        $updatedUserStocksMovement = $this->repository->update(
            $userStocksMovement,
            $userStocksMovementCreateUpdateDTO
        );

        return $updatedUserStocksMovement;
    }

    public function delete(int $stocks_movement_id): array
    {
        try {
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
                    'message' => "Erro ao excluir movimento {$userStockMovement->id}. Não há quantidade suficiente. quantidade após movimentação: {$userStocks->quantity}!"
                ];
            }

            $userStocks->updateOrFail();
            $this->repository->delete($stocks_movement_id);
            
            return [
                'user_stocks_id' => $userStocks->id,
                'error' => false
            ];
        } catch (\Throwable $error) {
            $this->repository->rollback();
               
            return [
                'user_stocks_id' => $userStocks->id ?? null,
                'error' => true,
                'message' => 'Erro ao excluir movimento. Verifique! ' . $error->getMessage()
            ];
        }
    }

    public function buy(UserStocks $userStocks, string $quantity, string $value): void
    {
        $value_total_buy        = $this->valueTotal($quantity, $value, self::DECIMALS_TWELVE);
        $user_stock_value_total = $this->valueTotal((string) $userStocks->quantity, $userStocks->average_value, self::DECIMALS_TWELVE);

        $userStocks->quantity   = $this->newQuantity((string) $userStocks->quantity, $quantity, self::BUY);
        $user_stock_value_total = self::add($user_stock_value_total, $value_total_buy, self::DECIMALS_EIGHT);

        $userStocks->average_value = $this->calculateAverageValue($user_stock_value_total, $userStocks->quantity);
    }

    public function sale(UserStocks $userStocks, string $quantity, UserStocksMovementCreateUpdateDTO $dto): void
    {
        $dto->average_value  = $userStocks->average_value;
        $userStocks->quantity = $this->newQuantity((string) $userStocks->quantity, $quantity, self::SALE);
    }

    public function revertBuy(UserStocksMovement $userStocksMovement, UserStocks $userStocks): void
    {
        $userStocks->value_total = $this->valueTotal((string) $userStocks->quantity, $userStocks->average_value, self::DECIMALS_EIGHT);
        $userStocks->value_total = self::sub($userStocks->value_total, $userStocksMovement->value_total, self::DECIMALS_EIGHT);
        $userStocks->quantity    = $this->newQuantity((string) $userStocks->quantity, (string) $userStocksMovement->quantity, self::SALE);

        $userStocks->average_value = $userStocks->quantity > 0
                ? $this->calculateAverageValue($userStocks->value_total, $userStocks->quantity) 
                : '0.00';

        unset($userStocks->value_total); //exclui value_total pois nao existe este campo no modelo
    }

    public function revertSale(UserStocksMovement $userStocksMovement, UserStocks $userStocks): void
    {
        $this->buy($userStocks, (string) $userStocksMovement->quantity, $userStocksMovement->average_value);
    }

    public static function calculateGainPercent(string $gain, string $average_value): string
    {
        if ($average_value == '0.00') {
            return $average_value;
        }
        
        $gain_Percent = self::div($gain, $average_value, self::DECIMALS_EIGHT);
        $gain_Percent = self::mult($gain_Percent, "100", self::DECIMALS_EIGHT);
        $gain_Percent = sprintf('%.2f', $gain_Percent);
        
        return $gain_Percent;
    }

    public function valueTotal(string $quantity, string $value, ?int $decimals = self::DECIMALS_TWELVE): string
    {
        $valueTotal = self::mult($quantity, $value, $decimals);

        return sprintf('%.8f', $valueTotal);
    }

    /**
     * @param int $addOrSub 1 = add, 2 = sub
     * @return string
     */
    public function newQuantity(string $quantity, string $quantity2, int $addOrSub): string
    {
        return match ($addOrSub) {
            1 => self::add($quantity, $quantity2, self::DECIMALS_NO),
            2 => self::sub($quantity, $quantity2, self::DECIMALS_NO),
            default => throw new Exception("Tipo de calculo inválido! Class: UserStocksMovementService. Method: newQuantity. Valor informado: {$addOrSub}.")
        };
    }

    public function calculateAverageValue(string $value, string $quantity): string
    {
        $averageValue = self::div($value, $quantity, self::DECIMALS_TWELVE);

        //if ($averageValue < 0.01) $averageValue = '0.00';

        return sprintf('%.8f', $averageValue);
    }
}
