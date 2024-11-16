<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\stocks\UserStocksCreateUpdateDTO;
use App\Http\Requests\UserStocksRequest;
use App\Models\UserStocks;
use App\Services\{UserStocksMovementService, UserStocksService};
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class UserStocksController extends Controller
{
    public function __construct(
        protected UserStocksService $service,
        protected UserStocksMovementService $userStocksMovementService,
        protected StocksController $stocksController,
    ) {
        //
    }

    public function getUserStocks(): Collection
    {   
        $user_id = Auth::user()->id;
        $userStocksAndTotalizers = $this->service->getAll($user_id);

        return collect([
            'userAllStocks' => $userStocksAndTotalizers['userAllStocks'],
            'totalizers'    => $userStocksAndTotalizers['totalizers']
        ]);
    }
    
    public function show(int $user_stocks_id): View
    {
        $user_id             = Auth::id();
        $showUserStocks      = $this->service->userStocksWithGain($user_stocks_id);
        $userStocksMovements = $this->userStocksMovementService->getAll($user_id, $showUserStocks->stocks_id);
        
        return view('main.userStocks.userStocks', compact('showUserStocks', 'userStocksMovements'));
    }

    public function create(): View
    {
        $stocks = $this->stocksController->all();

        $disabledSelect = false;

        return view('main.userStocks.createUserStock', compact('stocks', 'disabledSelect'));
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
        $this->service->insert(
            UserStocksCreateUpdateDTO::make($userStocksRequest)
        );

        return redirect()
                ->route('dashboard', status: 201)
                ->with(['message' => "Ação incluída com sucesso!"]);
    }

    public function edit(UserStocks $userStocks): View
    {
        $userStocks = $userStocks->with('stocks')->find($userStocks->id);
        $stocks = collect([$userStocks->stocks]);

        $disabledSelect = true;
        
        return view('main.userStocks.alterUserStock', compact('userStocks', 'stocks', 'disabledSelect'));
    }

    public function update(UserStocks $userStocks, UserStocksRequest $userStocksRequest): RedirectResponse
    {
        $userStocks = $this->service->update($userStocks, UserStocksCreateUpdateDTO::make($userStocksRequest));

        return redirect()
                ->route('userStocks.edit', $userStocks->id)
                ->with(['message' => "Ação alterada com sucesso!"], compact('userStocks'));
    }

    public function destroy(int $user_stocks_id): RedirectResponse
    {
        $response = $this->service->delete($user_stocks_id);

        if ($response['status']) {
            return redirect()
                    ->route('dashboard')
                    ->with(['message' => $response['message']]);
        }
        
        return redirect()
                ->back()
                ->withErrors(['error' => $response['message']]);
    }
}
