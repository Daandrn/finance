<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\title\{TitleCreateDTO, TitleUpdateDTO};
use App\Models\Title;
use App\Repositories\TitleRepository;
use App\Traits\{MoneyOperations, Scales};
use Illuminate\Support\Collection;

class TitleService
{
    use MoneyOperations;
    use Scales;

    public function __construct(
        protected TitleRepository $titleRepository,
        protected SelicApiService $selicApiService,
    ) {
        //
    }

    public function getAll(int $user_id): Collection
    {
        $titles = $this->titleRepository->all($user_id);

        $totalizersInit = collect([
            'patrimony'               => "0.00",
            'buy_cumulative'          => "0.00",
            'gain_cumulative'         => "0.00",
            'gain_percent_cumulative' => "0.00",
        ]);
        
        if ($titles->isEmpty()) {
            return collect([
                'titles' => $titles, 
                'totalizers' => $totalizersInit,
            ]);
        }

        $totalizers = $titles->reduce(function ($totalizers, $title) {
            $title->gain         = self::calculateGain($title->value_current, $title->value_buy);
            $title->gain_percent = self::calculateGainPercent($title->gain, $title->value_buy);
    
            if ($title->tax === "SELIC") {
                $title->tax = $this->selicApiService->getCurrentSelic();
            }
    
            $totalizers['patrimony']       = self::add($totalizers['patrimony'], $title->value_current, self::TWO_DECIMALS);
            $totalizers['buy_cumulative']  = self::add($totalizers['buy_cumulative'], $title->value_buy, self::TWO_DECIMALS);
            $totalizers['gain_cumulative'] = self::calculateGain($totalizers['patrimony'], $totalizers['buy_cumulative']);
    
            return $totalizers;
        }, $totalizersInit);
    
        $totalizers['gain_percent_cumulative'] = self::calculateGainPercent($totalizers['gain_cumulative'], $totalizers['buy_cumulative']);
    
        return collect([
            'titles' => $titles,
            'totalizers' => $totalizers,
        ]);
    }

    public function get(string $id): Title
    {
        $oneTitle               = $this->titleRepository->get($id);
        $oneTitle->gain         = self::calculateGain($oneTitle->value_current, $oneTitle->value_buy);
        $oneTitle->gain_percent = self::calculateGainPercent($oneTitle->gain, $oneTitle->value_buy);

        return $oneTitle;
    }

    public function insert(TitleCreateDTO $TitleCreateDTO): Title
    {
        $insertedTitle = $this->titleRepository->insert($TitleCreateDTO);

        return $insertedTitle;
    }

    public function update(Title $title, TitleUpdateDTO $updateDTO): Title
    {
        $updatedTitle = $this->titleRepository->update($title, $updateDTO);

        return $updatedTitle;
    }

    public function delete(string $title_id): void
    {
        $this->titleRepository->delete((int) $title_id);

        return;
    }

    public static function irpfPrevision(): string
    {
        return 'a';
    }

    public static function calculateGain(string $value_current, string $value_buy): string
    {
        return $value_buy === '0.00'
                ? $value_buy
                : self::sub($value_current, $value_buy, 2);
    }

    public static function calculateGainPercent(string $gain, string $value_buy): string
    {
        if ($value_buy === '0.00') {
            return $value_buy;
        }
        
        $gain_Percent = self::div($gain, $value_buy, self::EIGHT_DECIMALS);
        $gain_Percent = self::mult($gain_Percent, "100", self::EIGHT_DECIMALS);
        $gain_Percent = sprintf('%.2f', $gain_Percent);

        return $gain_Percent;
    }
}
