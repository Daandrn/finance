<?php 

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\stocks\UserStocksMovementCreateUpdateDTO;
use App\Http\Requests\UserStocksMovementRequest;
use App\Imports\UserStocksMovementsImport;
use App\Models\{StocksMovementType, UserStocksMovement};
use App\Services\UserStocksMovementService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class UserStocksMovementController extends Controller
{
    public function __construct(
        protected StocksController $stocksController,
        protected StocksMovementType $stocksMovementType,
        protected UserStocksMovementService $service,
        protected UserStocksMovementsImport $import,
        protected Excel $excel,
    ) {
        //
    }

    public function getAll(): Collection
    {
        $user_id = Auth::user()->id;
        $userStocksAndTotalizers = $this->service->getAll($user_id);

        return collect([
            'userAllStocks' => $userStocksAndTotalizers['userAllStocks'], 
            'totalizers'    => $userStocksAndTotalizers['totalizers']
        ]);
    }

    public function show(int $ticker_id): View
    {
        $user_id             = Auth::id();
        $userStocksMovement  = $this->service->get($ticker_id);
        $userStocksMovements = $this->service->getAll($user_id, $ticker_id);

        return view('main.userStocksMovement.userStocksMovement', compact('userStocksMovement', 'userStocksMovements'));
    }

    public function create(int $stocks_id = null): View
    {
        $stocks = $this->stocksController->all();

        $selectedStocks = empty($stocks_id) ? null : $stocks->find($stocks_id);

        $userMovementType = $this->stocksMovementType->get();

        return view('main.userStocksMovement.createUserStockMovement', compact('stocks', 'userMovementType', 'selectedStocks'));
    }

    public function import(Request $request): RedirectResponse
    {
        $file = $request->file('fileUpload');
        $xlsx = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
        
        $message = match (true) {
            empty($file)         => 'Anexe um arquivo.',
            !$file->isValid()    => 'Anexe um arquivo válido. Formato aceito: XLSX. Em breve: CSV!',
            $file->getMimeType() !== $xlsx => 'A extensão do arquivo é inválida. Extensões aceitas: XLSX. Em breve: CSV!',
            !$file->isReadable() => 'O arquivo nao permite leitura. Verifique!',
            default => null
        };

        if (isset($message)) {
            return redirect()
                ->back()
                ->withErrors(['error' => $message]);
        }

        $file->store('files');

        DB::beginTransaction();
        
        try {
            $this->excel::import(
                $this->import, 
                $file, 
                null, 
                ExcelType::XLSX
            );

            DB::commit();
        } catch (\Throwable $error) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withErrors(['error' => $error->getMessage()]);
        }

        return redirect()
            ->route('dashboard', status: 201)
            ->with(['message' => "Importação realizada com sucesso!"]);
    }

    public function store(UserStocksMovementRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        
        $userStocksMovement = $this->service->insert(
            UserStocksMovementCreateUpdateDTO::make($request)
        );

        if (!$userStocksMovement['status']) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $userStocksMovement['errors']]);
        }

        DB::commit();
        
        return redirect()
            ->route('userStocks.show', $userStocksMovement['body']->user_stocks_id, status: 201)
            ->with('message', $userStocksMovement['message']);
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
        $userStocksMovement = $this->service->update(
            $userStocksMovement, 
            UserStocksMovementCreateUpdateDTO::make($userStocksMovementRequest)
        );

        return redirect()
                ->route('userStocksMovement.edit', $userStocksMovement->id)
                ->with(['message' => "Movimento alterado com sucesso!"], compact('userStocksMoviment'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $movementsDelete = collect($request->movementsDelete)
            ->map(function ($item) {
                $item = explode( ', ', $item);

                $item[1] = Carbon::parse($item[1]);

                return $item;
            })
            ->sortByDesc(function ($item) {
                return $item[1];
            });
        
        DB::beginTransaction();
        
        foreach ($movementsDelete as $item) {
            $response = $this->service->delete((int) $item[0]);

            if ($response['error']) break;

            $movementsDeleted[] = $item[0];
        }
        
        if ($response['error']) {
            return redirect()
                ->back()
                ->withErrors(['error' => $response['message']]);
        }
            
        DB::commit();
        
        return redirect()
            ->route('userStocks.show', $response['user_stocks_id'])
            ->with(['message' => "Movimento(s) " . implode(', ', $movementsDeleted) . " excluído(s) com sucesso!"]);
    }
}
