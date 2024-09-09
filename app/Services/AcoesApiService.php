<?php declare(strict_types=1);

namespace App\Services;

use App\RepositoriesApi\AcoesRepository;

class AcoesApiService
{
    public function __construct(
        protected AcoesRepository $acoesRepository,
    ) {
    }

    public function getCurrentValue(string $ticker)
    {
        $current = $this->acoesRepository->getCurrentValue($ticker);

        return $current->isNotEmpty()
                    ? $current->get('valor')
                    : null;
    }
}
