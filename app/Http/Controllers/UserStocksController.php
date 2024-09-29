<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\stocks\UserStocksCreateUpdateDTO;
use App\Http\Requests\UserStocksRequest;
use App\Models\UserStocks;
use App\Services\UserStocksMovementService;
use App\Services\UserStocksService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class UserStocksController extends Controller
{
    public function __construct(
        protected UserStocksService $userStocksService,
        protected UserStocksMovementService $userStocksMovementService,
        protected StocksController $stocksController,
    ) {
        //
    }

    public function getUserStocks(): Collection
    {   
        $user_id = Auth::user()->id;
        $userStocksAndTotalizers = $this->userStocksService->getAll($user_id);

        return collect([
            'userAllStocks' => $userStocksAndTotalizers['userAllStocks'], 
            'totalizers'    => $userStocksAndTotalizers['totalizers']
        ]);
    }
    
    public function show(int $user_stock_id): View
    {
        $user_id             = Auth::id();
        $showUserStocks      = $this->userStocksService->userStocksWithGain($user_stock_id);
        $userStocksMovements = $this->userStocksMovementService->getAll($user_id, $showUserStocks->stocks_id);

        return view('main.userStocks.userStocks', compact('showUserStocks', 'userStocksMovements'));
    }

    public function create(): View
    {
        $stocks = $this->stocksController->all();

        return view('main.userStocks.createUserStock', compact('stocks'));
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

    public function store(UserStocksRequest $userStocksRequest): RedirectResponse
    {
        $this->userStocksService->insert(
            UserStocksCreateUpdateDTO::make($userStocksRequest)
        );

        return redirect()
                ->route('dashboard', status: 201)
                ->with(['message' => "Ação incluída com sucesso!"]);
    }

    public function edit(UserStocks $userStocks): View
    {
        $userStocks = $userStocks->with('stocks')->firstOrFail();
        $stocks = $this->stocksController->all()->where('id', '=', $userStocks->stocks_id);
        
        return view('main.userStocks.alterUserStock', compact('userStocks', 'stocks'));
    }

    public function update(UserStocks $userStocks, UserStocksRequest $userStocksRequest): RedirectResponse
    {
        $userStocks = $this->userStocksService->update($userStocks, UserStocksCreateUpdateDTO::make($userStocksRequest));

        return redirect()
                ->route('userStocks.edit', $userStocks->id)
                ->with(['message' => "Ação alterada com sucesso!"], compact('userStocks'));
    }

    public function destroy(int $userStocks_id): RedirectResponse
    {
        $this->userStocksService->delete($userStocks_id);

        return redirect()
                ->route('dashboard')
                ->with(['message' => "Ação excluído com sucesso!"]);
    }
}
