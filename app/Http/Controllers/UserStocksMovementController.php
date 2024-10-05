<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\stocks\UserStocksMovementCreateUpdateDTO;
use App\Http\Requests\UserStocksMovementRequest;
use App\Imports\UserStocksMovementsImport;
use App\Models\StocksMovementType;
use App\Models\UserStocksMovement;
use App\Services\UserStocksMovementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class UserStocksMovementController extends Controller
{
    public function __construct(
        protected StocksController $stocksController,
        protected StocksMovementType $stocksMovementType,
        protected UserStocksMovementService $userStocksMovementService,
        protected UserStocksMovementsImport $userStocksMovementsImport,
    ) {
        //
    }

    public function getAll(): Collection
    {
        $user_id = Auth::user()->id;
        $userStocksAndTotalizers = $this->userStocksMovementService->getAll($user_id);

        return collect([
            'userAllStocks' => $userStocksAndTotalizers['userAllStocks'], 
            'totalizers'    => $userStocksAndTotalizers['totalizers']
        ]);
    }

    public function show(int $ticker_id): View
    {
        $user_id                = Auth::id();
        $showUserStocksMovement = $this->userStocksMovementService->get($ticker_id);
        $userStocksMovements    = $this->userStocksMovementService->getAll($user_id, $ticker_id);

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
        $file = $request->file('fileUpload');
        $xlsxVerify = $file->getMimeType() !== "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
        
        $message = match (true) {
            empty($file)         => 'Anexe um arquivo.',
            !$file->isValid()    => 'Arquivo inválido. Formato aceito: XLSX. Em breve: CSV!',
            $xlsxVerify          => 'A extensão do arquivo é inválida. Extensões aceitas: XLSX. Em breve: CSV!',
            !$file->isReadable() => 'O arquivo nao permite leitura. Verifique!',
            default => null
        };

        if (isset($message)) {
            return redirect()
                ->back()
                ->withErrors(['error' => $message]);
        }

        $file->store('files');

        set_time_limit(300);
        DB::beginTransaction();
        
        Excel::import(
            $this->userStocksMovementsImport, 
            $file, 
            null, 
            ExcelType::XLSX
        );

        DB::commit();
        set_time_limit(30);

        return redirect()
                ->route('dashboard', status: 201)
                ->with(['message' => "Importação realizada com sucesso!"]);
    }

    public function store(UserStocksMovementRequest $userStocksMovementRequest): RedirectResponse
    {
        $userStocksMovement = $this->userStocksMovementService->insert(
            UserStocksMovementCreateUpdateDTO::make($userStocksMovementRequest)
        );

        if ($userStocksMovement['status']) {
            return redirect()
                    ->route('userStocks.show', $userStocksMovement['body']->user_stocks_id, status: 201)
                    ->with('message', $userStocksMovement['message']);
        }
        
        return redirect()
                ->route('dashboard')
                ->with('errors', $userStocksMovement['errors']);
    }

    public function edit(UserStocksMovement $userStocksMovement): View
    {
        $userStocksMovement = $userStocksMovement->with('stocks')->firstOrFail();
        $stocks = $this->stocksController
                    ->all()
                    ->where('id', '=', $userStocksMovement->stocks_id);

        return view('main.userStocksMovement.alterUserStockMovement', compact('userStocksMovement', 'stocks'));
    }

    public function update(UserStocksMovement $userStocksMovement, UserStocksMovementRequest $userStocksMovementRequest): RedirectResponse
    {
        $userStocksMovement = $this->userStocksMovementService->update(
            $userStocksMovement, 
            UserStocksMovementCreateUpdateDTO::make($userStocksMovementRequest)
        );

        return redirect()
                ->route('userStocksMovement.edit', $userStocksMovement->id)
                ->with(['message' => "Movimento alterado com sucesso!"], compact('userStocksMoviment'));
    }

    public function destroy(int $user_stock_movement_id): RedirectResponse
    {
        $userStocks = $this->userStocksMovementService->delete($user_stock_movement_id);

        return redirect()
                ->route('userStocks.show', $userStocks)
                ->with(['message' => "Movimento excluído com sucesso!"]);
    }
}
