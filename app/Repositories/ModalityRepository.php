<?php declare(strict_types=1);

namespace App\Repositories;

use App\DTO\modality\ModalityCreateUpdateDTO;
use App\Models\Modality;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModalityRepository
{
    public function __construct(
        protected Modality $modality,
    ) {
    }

    public function all(string $direction = 'asc'): Collection
    {
        $modalities = $this->modality->orderBy('id', $direction)->get();
        
        return $modalities;
    }

    public function getOne(string $id): Model|null
    {
        $oneModality = $this->modality->findOrFail($id);

        return $oneModality;
    }

    public function new(ModalityCreateUpdateDTO $modalityCreateUpdateDTO): Modality
    {
        $newModality = $this->modality->create($modalityCreateUpdateDTO->toArray());

        return $newModality;
    }

    public function update(int $id, ModalityCreateUpdateDTO $modalityCreateUpdateDTO): bool
    {
        $updatedModality = $this->modality->findOrFail($id);

        return $updatedModality->updateOrFail($modalityCreateUpdateDTO->toArray());;
    }

    public function deleteOne(string $id): void
    {
        $modalityDeleted = $this->modality->findOrFail($id);
        $modalityDeleted->delete();

        $lastId = Modality::max('id');
        DB::statement("SELECT setval('modalities_id_seq', $lastId, true)");

        return;
    }
}
