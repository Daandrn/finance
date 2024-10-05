<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StocksRequest;
use App\Models\StocksType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\DTO\stocks\StocksCreateUpdateDTO;
use App\Repositories\StocksRepository;
use App\Services\StocksServiceApi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class StocksController extends Controller
{
    public function __construct(
        protected StocksRepository $stocksRepository,
        protected StocksServiceApi $stocksServiceApi,
    ) {
    }
    
    public function index(): View
    {
        $stocksPage = $this->stocksRepository->paginate(20);
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

    public function edit(int $id): view
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

    public function destroy(int $id): RedirectResponse
    {
        $stocksDeleted = $this->stocksRepository
                    ->get($id)
                    ->with('user_stocks')
                    ->find($id);

        if (isset($stocksDeleted->user_stocks)) {
            return redirect()
                ->back()
                ->withErrors(['error' => "Não foi possível realizar exclusão. O código de negociação {$stocksDeleted->ticker} já está sendo utilizado!"]);
        }
        
        $this->stocksRepository->delete($stocksDeleted);

        return redirect()
                ->route('stocks')
                ->with(['message' => "Código de negociação {$stocksDeleted->ticker} excluído com sucesso!"]);
    }

    public function all(): Collection
    {
        $stocks = $this->stocksRepository->all();

        return $stocks;
    }

    public function updateStocksValues(): RedirectResponse
    {
        $stocks = $this->stocksRepository->all();

        $stocks = $stocks->filter(function ($stocks) {
            return empty($stocks->last_update_values) || Carbon::parse($stocks->last_update_values)->lt('today');
        });

        if ($stocks->isEmpty()) {
            return redirect()
                    ->route('stocks')
                    ->with(['message' => "Nenhuma pendencia para atualizar!"]);
        }

        $stocks->get('ticker');
        $stocksValues = $this->stocksServiceApi->getStocksValues($stocks->toArray());

        if (
            isset($stocksValues['error'])
            && $stocksValues['error']
        ) {
            return redirect()
                ->route('stocks')
                ->withErrors($stocksValues['message']);
        }
        
        $stocks->each(function ($stocks) use ($stocksValues) {            
            $stocksDetails = $stocksValues[$stocks->ticker];

            $stocks->name               ??= substr($stocksDetails['longName'] ?? '', 0, 49);
            $stocks->current_value      = $stocksDetails['regularMarketPrice'] ?? $stocks->current_value;
            $stocks->high_value         = $stocksDetails['regularMarketDayHigh'] ?? $stocks->high_value;
            $stocks->low_value          = $stocksDetails['regularMarketDayLow'] ?? $stocks->low_value;
            $stocks->last_close_value   = $stocksDetails['regularMarketPreviousClose'] ?? $stocks->last_close_value;
            $stocks->last_update_values = Carbon::parse('now');

            $stocks->save();
        });
        
        return redirect()
                ->route('stocks')
                ->with(['message' => "Ações atualizadas com sucesso!"]);
    }
}
