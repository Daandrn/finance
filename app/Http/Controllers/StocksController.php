<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StocksRequest;
use App\Models\StocksType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\DTO\stocks\StocksCreateUpdateDTO;
use App\Repositories\StocksRepository;
use Illuminate\Database\Eloquent\Collection;

class StocksController extends Controller
{
    public function __construct(
        protected StocksRepository $stocksRepository,
    ) {
    }
    
    public function index(): View
    {
        $stocksPage = $this->stocksRepository->paginate(15);
        $stocksTypes = StocksType::all();
        
        return view('administrator.stocks.stocks', compact('stocksPage', 'stocksTypes'));
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
        $stocksEdit = $this->stocksRepository->get($id);
        $stocksTypes = StocksType::all();
        
        return view('administrator.stocks.alterStocks', compact('stocksEdit', 'stocksTypes'));
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

    public function all(): Collection
    {
        $stocks = $this->stocksRepository->all();

        return $stocks;
    }
}
