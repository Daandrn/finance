<?php declare(strict_types=1);

namespace App\DTO\split;

use App\Http\Requests\SplitRequest;
use Carbon\Carbon;

class SplitCreateUpdateDTO
{
    public function __construct(
        public int $stocks_id,
        public Carbon $date,
        public int $grouping,
        public int $split,
    ) {
        //
    }

    public static function make(SplitRequest $request): self
    {
        return new self(
            (int) $request->stocks_id,
            Carbon::parse($request->date),
            (int) $request->grouping,
            (int) $request->split,
        );
    }

    public function toArray(): array
    {
        return [
            'stocks_id' => $this->stocks_id,
            'date'      => $this->date,
            'grouping'  => $this->grouping,
            'split'     => $this->split,
        ];
    }
}
