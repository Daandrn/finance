<?php declare(strict_types=1);

namespace App\Services;

use App\RepositoriesApi\BrapiStocksApi;
use App\RepositoriesApi\StocksApiInterface;
use Illuminate\Http\RedirectResponse;

class StocksServiceApi
{
    protected StocksApiInterface $stocksApi;
    protected string $errorMessage;
    protected bool $error;
    
    public function __construct(
        BrapiStocksApi $stocksApi,
    ) {
        $this->stocksApi = $stocksApi;
    }

    /**
     * @param array|string[] $ticker
     */
    public function getCurrentValue(string $ticker): array|RedirectResponse
    {
        $stocksValue = $this->stocksApi->getStocksValues([$ticker]);

        if ($this->stocksApi->errorExists()) {
            return redirect()
                    ->route('userStocksMovement.create')
                    ->withErrors("Erro ao consultar valor: {$this->stocksApi->errorMessage()}!");
        }
        
        return $stocksValue;
    }

    public function getStocksValues(array $stocks): array
    {
        $stocksValues = $this->stocksApi->getStocksValues($stocks);
        
        if ($this->stocksApi->errorExists()) {
            return [
                'error' => true,
                'message' => "Erro ao consultar valor: {$this->stocksApi->errorMessage()}"
            ];
        }

        return $stocksValues;
    }
}
