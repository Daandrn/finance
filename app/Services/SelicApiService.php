<?php declare(strict_types=1);

namespace App\Services;

use App\RepositoriesApi\SelicRepository;

class SelicApiService
{
    public function __construct(
        protected SelicRepository $repository,
    ) {
        //
    }

    public function getCurrentSelic()
    {
        $current = $this->repository->getCurrentSelic();

        return $current->isNotEmpty()
                    ? $current->value('valor')
                    : null;
    }
}
