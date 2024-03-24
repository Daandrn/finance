<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\title\{TitleCreateDTO, TitleUpdateDTO};
use App\Models\Title;
use App\Repositories\TitleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class TitleService
{
    public function __construct(
        protected TitleRepository $titleRepository,
    ) {
    }

    public function userAllTitles(int $user_id): Collection|null
    {
        $titles = $this->titleRepository->userAllTitles($user_id);
        
        $patrimony = "0.00";
        $buy_cumulative = "0.00";
        $gain_cumulative = "0.00";
        $gain_percent_cumulative = "0.00";
        
        if ($titles) {
            $titles->map(function ($title) use (&$patrimony, &$buy_cumulative, &$gain_cumulative) {
                $title->gain         = self::calculateGain($title->value_current, $title->value_buy);
                $title->gain_percent = self::calculateGainPercent($title->gain, $title->value_buy);
                
                $patrimony       = bcadd($patrimony, $title->value_current, 2);
                $buy_cumulative  = bcadd($buy_cumulative, $title->value_buy, 2);
                $gain_cumulative = self::calculateGain($patrimony, $buy_cumulative);
            });

            $gain_percent_cumulative = self::calculateGainPercent($gain_cumulative, $buy_cumulative);

            $attributes = (object) [
                'patrimony'               => $patrimony,
                'buy_cumulative'          => $buy_cumulative,
                'gain_cumulative'         => $gain_cumulative,
                'gain_percent_cumulative' => $gain_percent_cumulative,
            ];
            
            $titles->append([$attributes]);
        }
        
        return $titles;
    }

    public function userOneTitle(string $id): Title
    {
        $oneTitle               = $this->titleRepository->userOneTitle($id);
        $oneTitle->gain         = self::calculateGain($oneTitle->value_current, $oneTitle->value_buy);
        $oneTitle->gain_percent = self::calculateGainPercent($oneTitle->gain, $oneTitle->value_buy);
        
        return $oneTitle;
    }

    public function insert(TitleCreateDTO $createDTO): Title
    {
        $insertedTitle = $this->titleRepository->insert($createDTO);

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
        return bcsub($value_current, $value_buy, 2);
    }

    public static function calculateGainPercent(string $gain, string $value_buy): string
    {
        $gain_Percent = bcdiv($gain, $value_buy, 8);
        $gain_Percent = bcmul($gain_Percent, "100", 4);
        $gain_Percent = sprintf('%.2f', $gain_Percent);
        
        return $gain_Percent;
    }
}
