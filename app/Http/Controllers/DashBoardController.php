<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashBoardController extends Controller
{
    public function __construct(
        protected TitleController $titleController,
        protected UserStocksController $userStocksController,
    ) {
        //
    }

    public function index(): View
    {
        $userTitles = $this->titleController->userAllTitles();
        $userStocks = $this->userStocksController->userAllStocks();

        return view('main.dashboard', [
            'titles'               => $userTitles->get('titles'), 
            'totalizers'           => $userTitles->get('totalizers'),
            'userAllStocks'        => $userStocks->get('userAllStocks'), 
            'userStockstotalizers' => $userStocks->get('totalizers'),
        ]);
    }
}
