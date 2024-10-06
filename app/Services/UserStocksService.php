<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\stocks\UserStocksCreateUpdateDTO;
use App\Models\UserStocks;
use App\Repositories\UserStocksRepository;
use App\Traits\{MoneyOperations, Scales};
use Illuminate\Support\Collection;

class UserStocksService
{
    use MoneyOperations;
    use Scales;

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

            $userStocks->gain_total          = self::mult(strval($userStocks->quantity), $userStocks->gain, self::TWO_DECIMALS);
            $userStocks->value_total_buy     = self::mult(strval($userStocks->quantity), $userStocks->average_value, self::TWO_DECIMALS);
            $userStocks->value_total_current = self::mult(strval($userStocks->quantity), $userStocks->value_current, self::TWO_DECIMALS);

            $totalizers['buy_cumulative']  = self::add($totalizers['buy_cumulative'], $userStocks->value_total_buy, self::TWO_DECIMALS);
            $totalizers['patrimony']       = self::add($totalizers['patrimony'], $userStocks->value_total_current, self::TWO_DECIMALS);
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
        return $average_value === '0.00'
                ? $average_value
                : self::sub($value_current, $average_value, self::TWO_DECIMALS);
    }

    public static function calculateGainPercent(string $gain, string $average_value): string
    {
        if ($average_value === '0.00') {
            return $average_value;
        }
        
        $gain_Percent = self::div($gain, $average_value, self::EIGHT_DECIMALS);
        $gain_Percent = self::mult($gain_Percent, "100", self::EIGHT_DECIMALS);
        $gain_Percent = sprintf('%.2f', $gain_Percent);

        return $gain_Percent;
    }
}
