<?php declare(strict_types=1);

namespace Tests;

use App\Models\User;

class Teste
{
    public function __construct() 
    {
        //
    }
    
    public function index(User $user)
    {
        $users = $user->get();

        echo $users->firstOrFail();
    }
}

(new Teste())->index(new User());
