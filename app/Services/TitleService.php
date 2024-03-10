<?php declare(strict_types=1);

namespace App\Services;

use App\DTO\title\{TitleCreateDTO, TitleUpdateDTO};
use App\Models\Title;
use App\Repositories\TitleRepository;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

class TitleService
{
    public function __construct(
        protected TitleRepository $titleRepository,
    ) {
    }

    public function userAllTitles(int $user_id): Collection|null
    {
        $titles = $this->titleRepository->userAllTitles($user_id);

        return $titles;
    }

    public function insert(TitleCreateDTO $createDTO): stdClass|string
    {
        $insertedTitle = $this->titleRepository->insert($createDTO);

        return $insertedTitle;
    }

    public function update(Title $title, TitleUpdateDTO $updateDTO): stdClass
    {
        $updatedTitle = $this->titleRepository->update($title, $updateDTO);

        return $updatedTitle;
    }

    public function delete(string $title_id): void
    {
        $this->titleRepository->delete((int) $title_id);

        return;
    }
}
