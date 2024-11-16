<?php declare(strict_types=1);

namespace App\RepositoriesApi;

use App\Repositories\StocksRepository;
use Illuminate\Support\Facades\Http;

class BrapiStocksApi implements StocksApiInterface
{
    protected string $baseUrl;
    protected string $range = '1d';
    protected string $interval = '1d';
    protected bool $fundamental = false;
    protected bool $dividends = false;
    protected string $modules = 'balanceSheetHistory';
    private string $token;
    protected string $errorMessage;
    protected bool $error = false;

    public function __construct(
        protected Http $http,
        protected StocksRepository $stocksRepository,
    ) {
        $this->baseUrl = config('services.brapi.endpoint');
        $this->token   = config('services.brapi.key');
    }

    public function getCurrentValue(string $ticker): string|null
    {
        $response = $this->http->get(
            "{$this->baseUrl}/quote/{$ticker}",
            [
                'range'       => $this->range,
                'interval'    => $this->interval,
                'fundamental' => $this->fundamental,
                'dividends'   => $this->dividends,
                'modules'     => null,
                'token'       => $this->token,
            ]
        )->json();

        if (
            isset($response['error'])
            && $response['error'] == true
        ) {
            $this->error = $response['error'];
            $this->errorMessage = $response['message'];

            return null;
        }

        $stocksDetails = $response['results'][0];

        return (string) $stocksDetails['regularMarketPrice'];
    }

    public function getStocksValues(array $stocks): array|null
    {
        $stocksDetails = [];

        foreach ($stocks as $item) {
            $response = $this->http->get(
                "{$this->baseUrl}/quote/{$item['ticker']}",
                [
                    'range'       => $this->range,
                    'interval'    => $this->interval,
                    'fundamental' => $this->fundamental,
                    'dividends'   => $this->dividends,
                    'modules'     => null,
                    'token'       => $this->token,
                ]
            )->json();

            if (
                isset($response['error'])
                && $response['error'] == true
            ) {
                $this->error = $response['error'];
                $this->errorMessage = $response['message'];

                return null;
            }

            $stocksDetails[$item['ticker']] = $response['results'][0];
        }

        return $stocksDetails;
    }

    public function splits(string|array $stocks): array|null
    {
        if (is_string($stocks)) {
            $stocks[] = $stocks;
        }

        foreach ($stocks as $key => $value) {
            # code...
        }
        
        return [];
    }

    public function errorExists(): bool
    {
        return $this->error;
    }

    public function errorMessage(): string
    {
        return $this->errorMessage;
    }
}
