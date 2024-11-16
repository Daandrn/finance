<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\stocks\UserStocksCreateUpdateDTO;
use App\Http\Controllers\StocksController;
use App\Models\UserStocks;
use App\Repositories\UserStocksRepository;
use App\Traits\{Operations, Scales};
use Illuminate\Support\Collection;

class UserStocksService
{
    use Operations;
    use Scales;

    public function __construct(
        protected UserStocksRepository $repository,
        protected StocksController $stocksController,
    ) {
        //
    }

    public function getAll(int $user_id): Collection
    {
        $userStocks = $this->repository->all($user_id);

        $totalizersInit = collect([
            'patrimony'               => "0.00",
            'buy_cumulative'          => "0.00",
            'gain_cumulative'         => "0.00",
            'gain_percent_cumulative' => "0.00",
        ]);

        if ($userStocks->isEmpty()) {
            return collect([
                'userAllStocks' => $userStocks, 
                'totalizers'    => $totalizersInit,
            ]);
        }

        $totalizers = $userStocks->reduce(function ($totalizers, $userStocks) {
            $userStocks->value_current = $userStocks->stocks->current_value;
            $userStocks->gain          = self::calculateGain($userStocks->value_current, $userStocks->average_value);
            $userStocks->gain_percent  = self::calculateGainPercent($userStocks->gain, $userStocks->average_value);

            $userStocks->gain_total          = self::mult(strval($userStocks->quantity), $userStocks->gain, self::DECIMALS_EIGHT);
            $userStocks->value_total_buy     = self::mult(strval($userStocks->quantity), $userStocks->average_value, self::DECIMALS_EIGHT);
            $userStocks->value_total_current = self::mult(strval($userStocks->quantity), $userStocks->value_current, self::DECIMALS_EIGHT);

            $totalizers['buy_cumulative']  = self::add($totalizers['buy_cumulative'], $userStocks->value_total_buy, self::DECIMALS_EIGHT);
            $totalizers['patrimony']       = self::add($totalizers['patrimony'], $userStocks->value_total_current, self::DECIMALS_EIGHT);
            $totalizers['gain_cumulative'] = self::calculateGain($totalizers['patrimony'], $totalizers['buy_cumulative']);

            return $totalizers;
        }, $totalizersInit);

        $totalizers['gain_percent_cumulative'] = self::calculateGainPercent($totalizers['gain_cumulative'], $totalizers['buy_cumulative']);
        
        $userStocks = $userStocks->sortBy(function ($item) {
            return $item->value_total_current;
        }, descending: true);

        return collect([
            'userAllStocks' => $userStocks,
            'totalizers'    => $totalizers,
        ]);
    }

    public function forUpdateOrCreate(int $user_id, int $stocks_id, UserStocksCreateUpdateDTO $dto): UserStocks
    {
        return $this->repository->forUpdateOrCreate($user_id, $stocks_id, $dto);
    }

    public function forUpdate(int $user_stocks_id): UserStocks
    {
        return $this->repository->forUpdate($user_stocks_id);
    } 

    public function userStocksWithGain(int $user_stocks_id): UserStocks
    {
        $userStocks                = $this->repository->get($user_stocks_id);
        $userStocks->current_value = $userStocks->stocks->current_value;
        $userStocks->gain          = self::calculateGain($userStocks->current_value, $userStocks->average_value);
        $userStocks->gain_percent  = self::calculateGainPercent($userStocks->gain, $userStocks->average_value);

        return $userStocks;
    }

    public function insert(UserStocksCreateUpdateDTO $userStocksCreateUpdateDTO): UserStocks
    {
        $insertedUserStocks = $this->repository->insert($userStocksCreateUpdateDTO);

        return $insertedUserStocks;
    }

    public function update(UserStocks $userStocks, UserStocksCreateUpdateDTO $userStocksCreateUpdateDTO): UserStocks
    {
        $updatedUserStocks = $this->repository->update($userStocks, $userStocksCreateUpdateDTO);

        return $updatedUserStocks;
    }

    public function delete(int $user_stocks_id): array
    {
        $userStockDeleted = $this->repository->get($user_stocks_id);

        if ($userStockDeleted->user_stocks_movements->isNotEmpty()) {
            return [
                'message' => "Não foi posível excluir {$userStockDeleted->stocks->ticker} pois existem movimentações lançadas. Verifique!",
                'status'  => false
            ];
        }

        $this->repository->delete($userStockDeleted->id);

        return [
            'message' => "{$userStockDeleted->stocks->ticker} excluído(a) com sucesso!",
            'status'  => true
        ];
    }

    public static function irpfPrevision(): string
    {
        return 'a';
    }

    public static function calculateGain(string $value_current, string $average_value): string
    {
        return $average_value <= 0.01
                ? $average_value
                : self::sub($value_current, $average_value, self::DECIMALS_EIGHT);
    }

    public static function calculateGainPercent(string $gain, string $average_value): string
    {
        if ($average_value <= 0.01) {
            return $average_value;
        }

        $gain_Percent = self::div($gain, $average_value, self::DECIMALS_EIGHT);
        $gain_Percent = self::mult($gain_Percent, "100", self::DECIMALS_EIGHT);
        $gain_Percent = sprintf('%.2f', $gain_Percent);

        return $gain_Percent;
    }
}
