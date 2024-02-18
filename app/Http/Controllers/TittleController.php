<?php

namespace App\Http\Controllers;

use App\Http\Requests\TittleRequest;
use App\Service\TittleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TittleController extends Controller
{
    public function __construct(protected TittleService $tittle)
    {}
    
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tittles = $this->tittle->getAll();
        
        return view('teste', compact("tittles"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('teste');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TittleRequest $request): RedirectResponse
    {
        $tittle = $this->tittle->store($request);

        return redirect()
                ->route('teste')
                ->with(["message" => "Título incluído com sucesso"], compact("tittle"));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): view
    {
        $tittles = $this->tittle->findOrfail($id);

        return view('teste', compact('tittles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tittles = $this->tittle->findOrfail($id);

        return view('teste');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $tittles = $this->tittle->update($request);

        return redirect()
                ->route('teste', 200)
                ->with(["message" => "Título alterado com sucesso!"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->tittle->destroy($id);

        return redirect()
                ->route('teste')
                ->with(["message" => "Título alterado com sucesso!"]);
    }
}
