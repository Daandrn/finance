<?php 

namespace App\RepositoriesApi;

interface StocksApiInterface
{
    public function getCurrentValue(string $ticker): string|null;
}