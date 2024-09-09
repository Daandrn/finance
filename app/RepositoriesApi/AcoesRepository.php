<?php declare(strict_types=1);

namespace App\RepositoriesApi;

use Illuminate\Http\Client\Factory as Http;
use Illuminate\Support\Collection;

class AcoesRepository
{
    protected string $baseUrl;

    public function __construct(
        protected Http $http,
    ) {
        $codigoSGS     = 1178;
        $this->baseUrl = "";
    }

    public function getCurrentValue(string $ticker): Collection
    {
        // $dateNow = Carbon::parse()->format('d/m/Y');
        // $query   = [
        //     'formato'     => "json",
        //     'dataInicial' => $dateNow,
        //     'dataFinal'   => $dateNow,
        // ];

        // $response = $this->http
        //                  ->get($this->baseUrl, $query)
        //                  ->collect();
        
        return collect(['valor' => strval(21)]);
    }
}
