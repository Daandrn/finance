<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\Tittle\TittleCreateDTO;
use App\DTO\Tittle\TittleUpdateDTO;
use App\Http\Requests\TittleRequest;
use App\Models\Tittle;
use App\Services\TittleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TittleController extends Controller
{
    public function __construct(
        protected TittleService $tittleService,
    ) {
    }
    
    public function index(): View
    {
        $tittles = $this->tittleService->all();
        
        return view('teste', compact("tittles"));
    }
    
    public function show(string $tittle_id): view
    {
        $tittles = $this->tittleService->showOne($tittle_id);

        return view('teste', compact('tittles'));
    }

    public function create(): View
    {
        return view('teste');
    }

    public function store(TittleRequest $TittleRequest): RedirectResponse
    {
        $tittle = $this->tittleService->insert(
            TittleCreateDTO::DTO($TittleRequest)
        );

        return redirect()->route('teste', 201)
            ->with(["message" => "Título incluído com sucesso"], compact("tittle"));
    }

    public function edit(string $tittle_id): view
    {
        $tittles = $this->tittleService->showOne($tittle_id);

        return view('teste', compact('tittles'));
    }

    public function update(TittleRequest $TittleRequest): RedirectResponse
    {
        $tittles = $this->tittleService->update(
            TittleUpdateDTO::DTO($TittleRequest)
        );

        return redirect()->route('teste', 204)
            ->with(["message" => "Título alterado com sucesso!"]);
    }

    public function destroy(Tittle $tittle_id): RedirectResponse
    {
        $this->tittleService->delete($tittle_id);

        return redirect()->route('teste', 204)
            ->with(["message" => "Título excluído com sucesso!"]);
    }
}
