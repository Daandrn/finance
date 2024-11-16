<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\Split\SplitCreateUpdateDTO;
use App\Models\Split;
use App\Repositories\SplitRepository;
use App\Repositories\StocksRepository;
use Illuminate\Database\Eloquent\Collection;

class SplitService
{
    public function __construct(
        protected SplitRepository $repository,
        protected StocksRepository $stocksRepository,
    ) {
        //
    }

    public function get(?int $stocks_id): Collection
    {
        return $this->repository->get($stocks_id);
    }

    public function create(SplitCreateUpdateDTO $dto): array
    {
        $stocksExists = $this->stocksRepository->get($dto->stocks_id);
        
        if (empty($stocksExists)) {
            return [
                'error' => true,
                'Message' => "Inserido com sucesso!"
            ];
        };

        $this->repository->insert($dto);
            
        return [
            'error' => false,
            'Message' => "Inserido com sucesso!"
        ];
    }
}