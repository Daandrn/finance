<?php declare(strict_types=1);

namespace App\Services;

use App\RepositoriesApi\BrapiStocksApi;
use Illuminate\Http\RedirectResponse;

class StocksServiceApi
{
    protected string $errorMessage;
    protected bool $error;
    
    public function __construct(
        protected BrapiStocksApi $stocks,
    ) {
        //
    }

    /**
     * @param string|string[] $ticker
     */
    public function getCurrentValue(string $ticker): string|RedirectResponse
    {
        $stocksValue = $this->stocks->getCurrentValue($ticker);

        if ($this->stocks->errorExists()) {
            return redirect()
                    ->route('userStocksMovement.create')
                    ->withErrors("Erro ao consultar valor: {$this->stocks->errorMessage()}!");
        }
        
        return $stocksValue;
    }

    public function getStocksValues(array $stocks): array
    {
        $stocksValues = $this->stocks->getStocksValues($stocks);
        
        if ($this->stocks->errorExists()) {
            return [
                'error' => true,
                'message' => "Erro ao consultar valor: {$this->stocks->errorMessage()}"
            ];
        }

        return $stocksValues;
    }
}
