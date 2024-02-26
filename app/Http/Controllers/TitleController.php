<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\Title\TitleCreateDTO;
use App\DTO\Title\TitleUpdateDTO;
use App\Http\Requests\TitleRequest;
use App\Models\Title;
use App\Services\TitleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TitleController extends Controller
{
    public function __construct(
        protected TitleService $titleService,
    ) {
    }
    
    public function index(): View
    {
        $titles = $this->titleService->all();
        
        return view('main.dashboard', compact("titles"));
    }
    
    public function show(string $title_id): view
    {
        $titles = $this->titleService->showOne($title_id);

        return view('teste', compact('titles'));
    }

    public function create(): View
    {
        return view('teste');
    }

    public function store(TitleRequest $titleRequest): RedirectResponse
    {
        $Title = $this->titleService->insert(
            TitleCreateDTO::DTO($titleRequest)
        );

        return redirect()->route('teste', 201)
            ->with(["message" => "Título incluído com sucesso"], compact("title"));
    }

    public function edit(string $title_id): view
    {
        $titles = $this->titleService->showOne($title_id);

        return view('teste', compact('titles'));
    }

    public function update(TitleRequest $titleRequest): RedirectResponse
    {
        $titles = $this->titleService->update(
            TitleUpdateDTO::DTO($titleRequest)
        );

        return redirect()->route('teste', 204)
            ->with(["message" => "Título alterado com sucesso!"]);
    }

    public function destroy(Title $title_id): RedirectResponse
    {
        $this->titleService->delete($title_id);

        return redirect()->route('teste', 204)
            ->with(["message" => "Título excluído com sucesso!"]);
    }
}
