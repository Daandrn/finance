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
use Illuminate\Http\JsonResponse;

class StocksController extends Controller
{
    public function __construct(
        protected StocksRepository $repository,
        protected StocksServiceApi $stocksServiceApi,
    ) {
        //
    }
    
    public function index(): View
    {
        $stocksPage = $this->repository->paginate(20);
        $stocksTypes = StocksType::all();
        
        return view('administrator.stocks.stocks', compact('stocksPage', 'stocksTypes'));
    }

    public function store(StocksRequest $stocksRequest): RedirectResponse
    {
        $this->repository->new(
            StocksCreateUpdateDTO::make($stocksRequest),
        );

        return redirect()
                ->route('stocks')
                ->with(['message' => "Código de negociação criado com sucesso!"]);
    }

    public function edit(int $id): view
    {
        $stocksEdit = $this->repository->get($id);
        $stocksTypes = StocksType::all();
        
        return view('administrator.stocks.alterStocks', compact('stocksEdit', 'stocksTypes'));
    }

    public function update(int $id, StocksRequest $stocksRequest): RedirectResponse
    {
        $this->repository->update(
            $id,
            StocksCreateUpdateDTO::make($stocksRequest)
        );

        return redirect()
                ->route('stocks')
                ->with(['message' => "Código de negociação alterado com sucesso!"]);
    }

    public function destroy(int $id): RedirectResponse
    {
        $stocksDeleted = $this->repository->get($id)
            ->select([
                'stocks.id', 'stocks.ticker', 'user_stocks.stocks_id'
            ])
            ->leftJoin('user_stocks', 'stocks.id', '=', 'user_stocks.stocks_id')
            ->where('stocks.id', $id)
            ->first();
        
        if (isset($stocksDeleted->stocks_id)) {
            return redirect()
                ->back()
                ->withErrors(['error' => "Não foi possível realizar exclusão. O código de negociação {$stocksDeleted->ticker} já está sendo utilizado!"]);
        }
        
        $this->repository->delete($stocksDeleted);

        return redirect()
                ->route('stocks')
                ->with(['message' => "Código de negociação {$stocksDeleted->ticker} excluído com sucesso!"]);
    }

    public function all(): Collection
    {
        $stocks = $this->repository->all();

        return $stocks;
    }

    public function updateStocksValues(): JsonResponse
    {
        $stocks = $this->repository->all();

        $stocks = $stocks->filter(function ($stocks) {
            return 
                (
                    empty($stocks->last_update_values) 
                    || Carbon::parse($stocks->last_update_values)->lt('today')
                ) 
                && $stocks->status === true;
        });

        if ($stocks->isEmpty()) {
            return response()
                ->json([
                    'message' => "Nenhuma pendencia para atualizar!",
                    'error' => false
            ]);
        }

        $stocks->get('ticker');
        $stocksValues = $this->stocksServiceApi->getStocksValues($stocks->toArray());

        if (
            isset($stocksValues['error'])
            && $stocksValues['error']
        ) {
            return response()
                ->json([
                    'message' => $stocksValues['message'],
                    'error' => true
            ]);
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

        return response()
            ->json([
                'message' => "Ações atualizadas com sucesso!",
                'error' => false
        ]);
    }
}
