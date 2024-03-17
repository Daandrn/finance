<?php  declare(strict_types=1);

namespace App\Repositories;

use App\DTO\title\{TitleCreateDTO, TitleUpdateDTO};
use App\Models\Title;
use Illuminate\Database\Eloquent\Collection;

class TitleRepository
{
    public function __construct(
        protected Title $title,
    ) {
    }

    public function userAllTitles(int $user_id): Collection|null
    {
        $titles = $this->title->where('user_id', $user_id)
                                ->with('modality')
                                ->with('title_type')
                                ->get();

        return $titles->isNotEmpty()
                ? $titles
                : null;
    }

    public function userOneTitle(string $id): Title
    {
        $oneTitle = $this->title->findOrFail($id);
        
        return $oneTitle;
    }

    public function insert(TitleCreateDTO $createDto): Title
    {
        $insertedTitle = $this->title->create($createDto->toArray());
        
        return $insertedTitle;
    }

    public function update(Title $updatedTitle, TitleUpdateDTO $updateDto): Title
    {
        $updatedTitle->update($updateDto->toArray());

        return $updatedTitle;
    }

    public function delete(int $title_id): void
    {
        $this->title->destroy($title_id);

        return;
    }
}
