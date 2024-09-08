<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StocksRequest;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Stocks;
use App\DTO\stocks\StocksCreateUpdateDTO;
use App\Repositories\StocksRepository;

class StocksController extends Controller
{
    public function __construct(
        protected StocksRepository $stocksRepository,
    ) {
    }
    
    public function index(): View
    {
        $stocksPage = $this->stocksRepository->paginate(15);
        
        return view('administrator.stocks.stocks', compact('stocksPage'));
    }

    public function store(StocksRequest $stocksRequest): RedirectResponse
    {
        $this->stocksRepository->new(
            StocksCreateUpdateDTO::make($stocksRequest),
        );

        return redirect()
                ->route('stocks')
                ->with(['message' => "Código de negociação criado com sucesso!"]);
    }

    public function edit(string $id): view
    {
        $stocksEdit = $this->stocksRepository->getOne($id);
        
        return view('administrator.stocks.alterStocks', compact('stocksEdit'));
    }

    public function update(int $id, StocksRequest $stocksRequest): RedirectResponse
    {
        $this->stocksRepository->update(
            $id,
            StocksCreateUpdateDTO::make($stocksRequest)
        );

        return redirect()
                ->route('stocks')
                ->with(['message' => "Código de negociação alterado com sucesso!"]);
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->stocksRepository->deleteOne($id);

        return redirect()
                ->route('stocks')
                ->with(['message' => "Código de negociação excluído!"]);
    }
}
