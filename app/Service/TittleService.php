<?php 

namespace App\Service;

use App\DTO\Tittle\TittleCreateDTO;
use App\DTO\Tittle\TittleUpdateDTO;
use App\Http\Requests\TittleRequest;
use App\Models\Tittle;

class TittleService
{
    public function __construct(protected Tittle $model)
    {}

    public function getAll(): void
    {
        $this->model->getAll();
    }

    public function findOrfail(string $id): void
    {
        $this->model->findOrfail($id);
    }

    public function store(TittleRequest $request): void
    {
        $this->model->saveOrFail(TittleCreateDTO::DTO($request));
    }

    public function update(TittleRequest $request): void
    {
        $this->model->updateOrFail(TittleUpdateDTO::DTO($request));
    }

    public function destroy(string $id): void
    {
        $this->model->destroy($id);
    }
}
