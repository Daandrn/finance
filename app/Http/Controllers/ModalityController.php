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
        protected ModalityRepository $modalityRepository,
    ) {
    }
    
    public function index(Modality $modality): View
    {
        $modalities = $modality->orderBy('id')->simplePaginate(15);
        
        return view('administrator.modality.modalities', compact('modalities'));
    }

    /**
     * Sem uso
     */
    public function create()
    {
        return view('administrator.modality.createModalities');
    }

    public function store(ModalityRequest $modalityRequest): RedirectResponse
    {
        $this->modalityRepository->newModality(
            ModalityCreateUpdateDTO::DTO($modalityRequest),
        );

        return redirect()
                ->route('modalities')
                ->withe('message', "Modalidade criada com sucesso!");
    }

    public function edit(string $id): view
    {
        $modality = $this->modalityRepository->getOneModality($id);
        
        return view('administrator.modality.alterModalities', compact('modality'));
    }

    public function update(ModalityRequest $modalityRequest)
    {
        //
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->modalityRepository->deleteOne($id);

        return redirect()
                ->route('modalities')
                ->with('message', "Modalidade excluída!");
    }
}
