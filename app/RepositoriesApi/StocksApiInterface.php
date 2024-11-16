<?php 

namespace App\RepositoriesApi;

use App\Repositories\StocksRepository;
use Illuminate\Support\Facades\Http;

interface StocksApiInterface
{
    public function __construct(Http $http, StocksRepository $stocksRepository);
    public function getCurrentValue(string $ticker): string|null;
    public function getStocksValues(array $stocks): array|null;
    public function splits(string|array $stocks): array|null;
    public function errorExists(): bool;
    public function errorMessage(): string;
}
