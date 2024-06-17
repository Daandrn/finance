<?php declare(strict_types=1);

namespace App\RepositoriesApi;

use Carbon\Carbon;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Support\Collection;

class SelicRepository
{
    protected string $baseUrl;

    public function __construct(
        protected Http $http,
    ) {
        $codigoSGS     = 1178;
        $this->baseUrl = "https://api.bcb.gov.br/dados/serie/bcdata.sgs.$codigoSGS/dados";
    }
    
    /**
     * Retorna JSON contendo a selic do dia atual
     */
    public function getCurrentSelic(): Collection
    {
        $dateNow = Carbon::parse()->format('d/m/Y');
        $query   = [
            'formato'     => "json",
            'dataInicial' => $dateNow,
            'dataFinal'   => $dateNow,
        ];

        $response = $this->http
                         ->get($this->baseUrl, $query)
                         ->collect();
        
        return $response;
    }
}
