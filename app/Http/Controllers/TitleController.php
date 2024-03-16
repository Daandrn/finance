<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TitleRequest;
use App\DTO\title\{TitleCreateDTO, TitleUpdateDTO};
use App\Models\Title;
use App\Repositories\ModalityRepository;
use App\Services\TitleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TitleController extends Controller
{
    public function __construct(
        protected TitleService $titleService,
        protected ModalityRepository $modalityRepository,
    ) {
    }
    
    public function index(): View
    {
        $user_id = Auth::user()->id;
        $titles = $this->titleService->userAllTitles($user_id);
        
        return view('main.dashboard', compact('titles'));
    }
    
    public function show(Title $title): view
    {
        $title = $title->with('modality')->first();
        
        return view('main.titles.title', compact('title'));
    }

    public function create(): View
    {
        $modalities = $this->modalityRepository->allModalities();
        
        return view('main.titles.createTitle', compact('modalities'));
    }

    public function store(TitleRequest $titleRequest): RedirectResponse
    {
        $Title = $this->titleService->insert(
            TitleCreateDTO::DTO($titleRequest)
        );

        return redirect()
                ->route('dashboard', status: 201)
                ->with(['message' => "Título incluído com sucesso"]);
    }

    public function edit(Title $title): view
    {
        $modalities = $this->modalityRepository->allModalities();

        return view('main.titles.alterTitle', compact('title', 'modalities'));
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
