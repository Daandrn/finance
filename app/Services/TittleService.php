<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\Tittle\TittleCreateDTO;
use App\DTO\Tittle\TittleUpdateDTO;
use App\Models\Tittle;
use App\Repositories\TittleRepository;
use stdClass;

class TittleService
{
    public function __construct(
        protected TittleRepository $tittleRepository,
    ) {
    }

    public function all(): stdClass|null
    {
        $tittles = $this->tittleRepository->getAll();

        return $tittles;
    }

    public function showOne(string $tittle_id): stdClass
    {
        $oneTittle = $this->tittleRepository->findOne($tittle_id);

        return $oneTittle;
    }

    public function insert(TittleCreateDTO $createDTO): stdClass
    {
        $insertedTittle = $this->tittleRepository->insert($createDTO);

        return $insertedTittle;
    }

    public function update(TittleUpdateDTO $updateDTO): stdClass
    {
        $updatedTittle = $this->tittleRepository->update($updateDTO);

        return $updatedTittle;
    }

    public function delete(Tittle $tittle_id): void
    {
        $this->tittleRepository->delete($tittle_id);
    }
}
