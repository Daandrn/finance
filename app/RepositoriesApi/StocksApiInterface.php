<?php 

namespace App\RepositoriesApi;

use Illuminate\Support\Collection;

interface StocksApiInterface
{
    public function getCurrentValue(string $ticker): string|null;
}