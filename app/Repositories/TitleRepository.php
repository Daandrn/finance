<?php  declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Title\TitleCreateDTO;
use App\DTO\Title\TitleUpdateDTO;
use App\Models\Title;
use stdClass;

class TitleRepository
{
    public function __construct(
        protected Title $title,
    ) {
    }

    public function getAll(): stdClass|null
    {
        $titles = $this->title->select('*');
        dd($this->title);
        return $titles ? (object) $titles->toArray() :  null;
    }

    public function findOne(string $title_id): stdClass
    {
        $oneTitle = $this->title->findOrFail($title_id);

        return (object) $oneTitle->toArray();
    }

    public function insert(TitleCreateDTO $createDto): stdClass
    {
        $insertedTitle = $this->title->create((array) $createDto);
        
        return (object) $insertedTitle->toArray();
    }

    public function update(TitleUpdateDTO $updateDto): stdClass
    {
        $updatedTitle = $this->title->findOrFail($updateDto->id);
        $updatedTitle->update($updateDto);

        return (object) $updatedTitle->toArray();
    }

    public function delete(Title $title_id): void
    {
        $this->title->destroy($title_id);
    }
}
