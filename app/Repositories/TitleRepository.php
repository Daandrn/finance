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
        //
    }

    public function all(int $user_id): Collection
    {
        $titles = $this->title->where('user_id', $user_id)
                                ->with('modality')
                                ->with('title_type')
                                ->orderBy('date_due', 'asc')
                                ->get();

        return $titles;
    }

    public function get(string $id): Title
    {
        $oneTitle = $this->title->findOrFail($id);
        
        return $oneTitle;
    }

    public function insert(TitleCreateDTO $TitleCreateDto): Title
    {
        $insertedTitle = $this->title->create($TitleCreateDto->toArray());
        
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
