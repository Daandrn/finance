<?php declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Split\SplitCreateUpdateDTO;
use App\Models\Split;
use Illuminate\Database\Eloquent\Collection;

class SplitRepository
{
    public function __construct(
        protected Split $model
    ) {
        //
    }

    public function get(?int $stocks_id): Collection
    {
        $splits = $this->model->select();

        if (!empty($stocks_id)) {
            $splits->where('stocks_id', '=', $stocks_id);
        }
        
        return $splits->get();
    }

    public function insert(SplitCreateUpdateDTO $dto): Split
    {
        return $this->model->create($dto->toArray());
    }
}