<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\modality\ModalityCreateUpdateDTO;
use App\Http\Requests\ModalityRequest;
use App\Models\Modality;
use App\Repositories\ModalityRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ModalityController extends Controller
{
    public function __construct(
        protected ModalityRepository $repository,
    ) {
        //
    }
    
    public function index(Modality $modality): View
    {
        $modalities = $modality->orderBy('id')->simplePaginate(15);

        return view('administrator.modality.modalities', compact('modalities'));
    }

    public function store(ModalityRequest $modalityRequest): RedirectResponse
    {
        $this->repository->new(
            ModalityCreateUpdateDTO::make($modalityRequest),
        );

        return redirect()
                ->route('modalities')
                ->with(['message' => "Modalidade criada com sucesso!"]);
    }

    public function edit(string $id): view
    {
        $modalityEdit = $this->repository->get($id);

        return view('administrator.modality.alterModalities', compact('modalityEdit'));
    }

    public function update(int $id, ModalityRequest $modalityRequest): RedirectResponse
    {
        $this->repository->update(
            $id,
            ModalityCreateUpdateDTO::make($modalityRequest)
        );

        return redirect()
                ->route('modalities')
                ->with(['message' => "Modalidade alterada com sucesso!"]);
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->repository->delete($id);

        return redirect()
                ->route('modalities')
                ->with(['message' => "Modalidade excluída!"]);
    }
}
