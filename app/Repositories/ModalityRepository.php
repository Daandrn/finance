<?php declare(strict_types=1);

namespace App\Repositories;

use App\DTO\modality\ModalityCreateUpdateDTO;
use App\Models\Modality;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ModalityRepository
{
    public function __construct(
        protected Modality $modality,
    ) {
    }

    public function allModalities(string $direction = 'asc'): Collection|null
    {
        $modalities = $this->modality->orderBy('id', $direction)->get();
        
        return $modalities->isNotEmpty()
                ? $modalities
                : null;
    }

    public function getOneModality(string $id): Model|null
    {
        $oneModality = $this->modality->findOrFail($id);

        return $oneModality;
    }

    public function newModality(ModalityCreateUpdateDTO $modalityCreateUpdateDTO): Model
    {
        $newModality = $this->modality->create($modalityCreateUpdateDTO->toArray());

        return $newModality;
    }

    public function deleteOne(string $id): void
    {
        $modalityDeleted = $this->modality->findOrFail($id);
        $modalityDeleted->delete();

        return;
    }
}
