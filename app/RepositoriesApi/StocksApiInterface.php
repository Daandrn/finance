<?php 

namespace App\RepositoriesApi;

use App\Repositories\StocksRepository;
use Illuminate\Http\Client\Factory;

interface StocksApiInterface
{
    public function __construct(Factory $http, StocksRepository $stocksRepository);
    public function getCurrentValue(string $ticker): string|null;
    public function getStocksValues(array $stocks): array|null;
    public function errorExists(): bool;
    public function errorMessage(): string;
}
