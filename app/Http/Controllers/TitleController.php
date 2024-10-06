<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TitleRequest;
use App\DTO\title\{TitleCreateDTO, TitleUpdateDTO};
use App\Models\{Title, TitleType};
use App\Repositories\ModalityRepository;
use App\Services\TitleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TitleController extends Controller
{
    public function __construct(
        protected TitleService $titleService,
    ) {
        //
    }
    
    public function getUserTitles(): Collection
    {   
        $user_id = Auth::user()->id;
        $titlesAndTotalizers = $this->titleService->getAll($user_id);

        return collect([
            'titles'     => $titlesAndTotalizers['titles'], 
            'totalizers' => $titlesAndTotalizers['totalizers']
        ]);
    }
    
    public function show(string $id): view
    {
        $title = $this->titleService->get($id);
        
        return view('main.titles.title', compact('title'));
    }

    public function create(ModalityRepository $modalityRepository, TitleType $titleType): View
    {
        $modalities = $modalityRepository->all();
        $title_types = $titleType->orderBy('id')->get();
        
        return view('main.titles.createTitle', compact('modalities', 'title_types'));
    }

    public function import(Request $request): RedirectResponse
    {
        dd('Calma, ainda não está pronto. usar a Lib maatwebsite/excel para manipular xlsx');

        $dataPath = $request->file('fileUpload')->isValid() 
                    ? request()->file('fileUpload')->store('files')
                    : null;

        //dá para ler assim enquanto nao adiciono a lib
        $data = file_get_contents(__DIR__.'/../../../storage/app/'.$dataPath);

        return redirect()
                ->route('dashboard', status: 201)
                ->with(['message' => "Calma, isso ainda não funciona"]);
    }

    public function store(TitleRequest $titleRequest): RedirectResponse
    {
        $this->titleService->insert(
            TitleCreateDTO::make($titleRequest)
        );

        return redirect()
                ->route('dashboard', status: 201)
                ->with(['message' => "Título incluído com sucesso!"]);
    }

    public function edit(Title $title, ModalityRepository $modalityRepository, TitleType $titleType): View
    {
        $modalities  = $modalityRepository->all();
        $title_types = $titleType->orderBy('id')->get();
        

        return view('main.titles.alterTitle', compact('title', 'modalities', 'title_types'));
    }

    public function update(Title $title, TitleRequest $titleRequest): RedirectResponse
    {
        $title = $this->titleService->update($title, TitleUpdateDTO::make($titleRequest));

        return redirect()
                ->route('titles.edit', $title->id)
                ->with(['message' => "Título alterado com sucesso!"], compact('title'));
    }

    public function destroy(string $title_id): RedirectResponse
    {
        $this->titleService->delete($title_id);

        return redirect()
                ->route('dashboard')
                ->with(['message' => "Título excluído com sucesso!"]);
    }
}
