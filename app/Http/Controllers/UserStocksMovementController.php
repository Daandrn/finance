<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\stocks\UserStocksMovementCreateUpdateDTO;
use App\Http\Requests\UserStocksMovementRequest;
use App\Models\StocksMovementType;
use App\Models\UserStocksMovement;
use App\Services\UserStocksMovementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class UserStocksMovementController extends Controller
{
    public function __construct(
        protected StocksController $stocksController,
        protected StocksMovementType $stocksMovementType,
        protected UserStocksMovementService $userStocksMovementService,
    ) {
    }

    public function userAllStocksMovements(): Collection
    {
        $user_id = Auth::user()->id;
        $userStocksAndTotalizers = $this->userStocksMovementService->userAllStocksMovements($user_id);

        return collect([
            'userAllStocks' => $userStocksAndTotalizers['userAllStocks'], 
            'totalizers'    => $userStocksAndTotalizers['totalizers']
        ]);
    }

    public function show(int $ticker_id): View
    {
        $user_id                = Auth::id();
        $showUserStocksMovement = $this->userStocksMovementService->userOneStockMovement($ticker_id);
        $userStocksMovements    = $this->userStocksMovementService->userAllStocksMovements($user_id, $ticker_id);

        return view('main.userStocksMovement.userStocksMovement', compact('showUserStocksMovement', 'userStocksMovements'));
    }

    public function create(): View
    {
        $stocks = $this->stocksController->all();
        $userMovementType = $this->stocksMovementType->get();

        return view('main.userStocksMovement.createUserStockMovement', compact('stocks', 'userMovementType'));
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

    public function store(UserStocksMovementRequest $userStocksMovementRequest): RedirectResponse
    {
        $this->userStocksMovementService->insert(
            UserStocksMovementCreateUpdateDTO::make($userStocksMovementRequest)
        );

        return redirect()
                ->route('dashboard', status: 201)
                ->with(['message' => "Movemento incluído com sucesso!"]);
    }

    public function edit(UserStocksMovement $userStocksMovement): View
    {
        $userStocksMovement = $userStocksMovement->join('stocks', 'stocks_id', '=', 'stocks.id', 'inner')->firstOrFail();
        $stocks = $this->stocksController->all()->where('id', '=', $userStocksMovement->stocks_id);

        return view('main.userStocksMovement.alterUserStockMovement', compact('userStocksMovement', 'stocks'));
    }

    public function update(UserStocksMovement $userStocksMovement, UserStocksMovementRequest $userStocksMovementRequest): RedirectResponse
    {
        $userStocksMovement = $this->userStocksMovementService->update($userStocksMovement, UserStocksMovementCreateUpdateDTO::make($userStocksMovementRequest));

        return redirect()
                ->route('userStocksMovement.edit', $userStocksMovement->id)
                ->with(['message' => "Movimento alterado com sucesso!"], compact('userStocksMoviment'));
    }

    public function destroy(int $user_stock_movement_id): RedirectResponse
    {
        $this->userStocksMovementService->delete($user_stock_movement_id);

        return redirect()
                ->route('dashboard')
                ->with(['message' => "Movimento excluído com sucesso!"]);
    }
}
