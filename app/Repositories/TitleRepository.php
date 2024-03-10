<?php  declare(strict_types=1);

namespace App\Repositories;

use App\DTO\title\{TitleCreateDTO, TitleUpdateDTO};
use App\Models\Title;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

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
                                ->get();

        return $titles->isNotEmpty()
                ? $titles
                : null;
    }

    public function insert(TitleCreateDTO $createDto): stdClass
    {
        $insertedTitle = $this->title->create($createDto->toArray());
        
        return (object) $insertedTitle->toArray();
    }

    public function update(Title $updatedTitle, TitleUpdateDTO $updateDto): stdClass
    {
        $updatedTitle->update($updateDto->toArray());

        return (object) $updatedTitle->toArray();
    }

    public function delete(int $title_id): void
    {
        $this->title->destroy($title_id);

        return;
    }
}
