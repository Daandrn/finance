<?php  declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Tittle\TittleCreateDTO;
use App\DTO\Tittle\TittleUpdateDTO;
use App\Models\Tittle;
use stdClass;

class TittleRepository
{
    public function __construct(
        protected Tittle $tittle,
    ) {
    }

    public function getAll(): stdClass|null
    {
        $tittles = $this->tittle->getAll();
        
        return $tittles ? (object) $tittles->toArray() :  null;
    }

    public function findOne(string $tittle_id): stdClass
    {
        $oneTittle = $this->tittle->findOrFail($tittle_id);

        return (object) $oneTittle->toArray();
    }

    public function insert(TittleCreateDTO $createDto): stdClass
    {
        $insertedTittle = $this->tittle->create((array) $createDto);
        
        return (object) $insertedTittle->toArray();
    }

    public function update(TittleUpdateDTO $updateDto): stdClass
    {
        $updatedTittle = $this->tittle->findOrFail($updateDto->id);
        $updatedTittle->update($updateDto);

        return (object) $updatedTittle->toArray();
    }

    public function delete(Tittle $tittle_id): void
    {
        $this->tittle->destroy($tittle_id);
    }
}
