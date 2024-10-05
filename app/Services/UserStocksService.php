<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\stocks\UserStocksCreateUpdateDTO;
use App\Models\UserStocks;
use App\Repositories\UserStocksRepository;
use Illuminate\Support\Collection;

class UserStocksService
{
    public function __construct(
        protected UserStocksRepository $userStocksRepository,
        protected StocksServiceApi $stocksServiceApi,
    ) {
        //
    }

    public function getAll(int $user_id): Collection
    {
        $userStocks = $this->userStocksRepository->all($user_id);

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

            $userStocks->gain_total          = bcmul(strval($userStocks->quantity), $userStocks->gain, 2);
            $userStocks->value_total_buy     = bcmul(strval($userStocks->quantity), $userStocks->average_value, 2);
            $userStocks->value_total_current = bcmul(strval($userStocks->quantity), $userStocks->value_current, 2);

            $totalizers['buy_cumulative']  = bcadd($totalizers['buy_cumulative'], $userStocks->value_total_buy, 2);
            $totalizers['patrimony']       = bcadd($totalizers['patrimony'], $userStocks->value_total_current, 2);
            $totalizers['gain_cumulative'] = self::calculateGain($totalizers['patrimony'], $totalizers['buy_cumulative']);

            return $totalizers;
        }, $totalizersInit);

        $totalizers['gain_percent_cumulative'] = self::calculateGainPercent($totalizers['gain_cumulative'], $totalizers['buy_cumulative']);

        return collect([
            'userAllStocks' => $userStocks,
            'totalizers'    => $totalizers,
        ]);
    }

    public function forUpdateOrCreate(int $user_id, int $stocks_id, UserStocksCreateUpdateDTO $dto): UserStocks
    {
        return $this->userStocksRepository->forUpdateOrCreate($user_id, $stocks_id, $dto);
    }

    public function forUpdate(int $user_stocks_id): UserStocks
    {
        return $this->userStocksRepository->forUpdate($user_stocks_id);
    } 

    public function userStocksWithGain(int $user_stocks_id): UserStocks
    {
        $oneUserStocks                = $this->userStocksRepository->get($user_stocks_id);
        $oneUserStocks->current_value = $oneUserStocks->stocks->current_value;
        $oneUserStocks->gain          = self::calculateGain($oneUserStocks->current_value, $oneUserStocks->average_value);
        $oneUserStocks->gain_percent  = self::calculateGainPercent($oneUserStocks->gain, $oneUserStocks->average_value);

        return $oneUserStocks;
    }

    public function insert(UserStocksCreateUpdateDTO $userStocksCreateUpdateDTO): UserStocks
    {
        $insertedUserStocks = $this->userStocksRepository->insert($userStocksCreateUpdateDTO);

        return $insertedUserStocks;
    }

    public function update(UserStocks $userStocks, UserStocksCreateUpdateDTO $userStocksCreateUpdateDTO): UserStocks
    {
        $updatedUserStocks = $this->userStocksRepository->update($userStocks, $userStocksCreateUpdateDTO);

        return $updatedUserStocks;
    }

    public function delete(int $user_stocks_id): array
    {
        $userStockDeleted = $this->userStocksRepository->get($user_stocks_id);

        if ($userStockDeleted->user_stocks_movements->isNotEmpty()) {
            return [
                'message' => "Não foi posível excluir {$userStockDeleted->stocks->ticker} pois existem movimentações lançadas. Verifique!",
                'status'  => false
            ];
        }

        $this->userStocksRepository->delete($userStockDeleted->id);

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
