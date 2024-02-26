<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\Title\TitleCreateDTO;
use App\DTO\Title\TitleUpdateDTO;
use App\Models\Title;
use App\Repositories\TitleRepository;
use stdClass;

class TitleService
{
    public function __construct(
        protected TitleRepository $titleRepository,
    ) {
    }

    public function all(): stdClass|null
    {
        $titles = $this->titleRepository->getAll();

        return $titles;
    }

    public function showOne(string $title_id): stdClass
    {
        $oneTitle = $this->titleRepository->findOne($title_id);

        return $oneTitle;
    }

    public function insert(TitleCreateDTO $createDTO): stdClass
    {
        $insertedTitle = $this->titleRepository->insert($createDTO);

        return $insertedTitle;
    }

    public function update(TitleUpdateDTO $updateDTO): stdClass
    {
        $updatedTitle = $this->titleRepository->update($updateDTO);

        return $updatedTitle;
    }

    public function delete(Title $title_id): void
    {
        $this->titleRepository->delete($title_id);
    }
}
