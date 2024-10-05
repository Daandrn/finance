<?php declare(strict_types=1);

namespace Tests;

use App\Models\Stocks;
use App\Models\User;
use Carbon\Carbon;

class Teste
{
    public function __construct() 
    {
        //
    }
    
    public function index()
    {
        //
    }
}

dd(
    Carbon::parse(Stocks::find(1)->last_update_values)->lt('today')
);
