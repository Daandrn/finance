<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TitleRequest;
use App\DTO\title\{TitleCreateDTO, TitleUpdateDTO};
use App\Models\Title;
use App\Models\TitleType;
use App\Repositories\ModalityRepository;
use App\Services\TitleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TitleController extends Controller
{
    public function __construct(
        protected TitleService $titleService,
    ) {
    }
    
    public function index(): View
    {
        $user_id = Auth::user()->id;
        $titles = $this->titleService->userAllTitles($user_id);

        $lastTitle = $titles 
                        ? $titles->last()->getAppends()[0]
                        : $titles;
        
        return view('main.dashboard', compact('titles', 'lastTitle'));
    }
    
    public function show(string $id): view
    {
        $title = $this->titleService->userOneTitle($id);
        
        return view('main.titles.title', compact('title'));
    }

    public function create(ModalityRepository $modalityRepository, TitleType $titleType): View
    {
        $modalities = $modalityRepository->allModalities();
        $title_types = $titleType->orderBy('id')->get();
        
        return view('main.titles.createTitle', compact('modalities', 'title_types'));
    }

    public function store(TitleRequest $titleRequest): RedirectResponse
    {
        $this->titleService->insert(
            TitleCreateDTO::DTO($titleRequest)
        );

        return redirect()
                ->route('dashboard', status: 201)
                ->with(['message' => "Título incluído com sucesso"]);
    }

    public function edit(Title $title, ModalityRepository $modalityRepository, TitleType $titleType): View
    {
        $modalities = $modalityRepository->allModalities();
        $title_types = $titleType->orderBy('id')->get();

        return view('main.titles.alterTitle', compact('title', 'modalities', 'title_types'));
    }

    public function update(Title $title, TitleRequest $titleRequest): RedirectResponse
    {
        $title = $this->titleService->update($title, TitleUpdateDTO::DTO($titleRequest));

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
