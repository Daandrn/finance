<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Request;

class DashBoardController extends Controller
{
    public function __construct(
        protected TitleController $titleController,
        protected UserStocksController $userStocksController,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $userTitles = $this->titleController->getUserTitles();
        $userStocks = $this->userStocksController->getUserStocks();
        $userAllStocks = $userStocks->get('userAllStocks');

        $currentPage = $request->get('page', 1);
        $perPage = 20; 
        $total = count($userAllStocks); 

        $itemsForCurrentPage = $userAllStocks->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            items: $itemsForCurrentPage,
            total: $total,
            perPage: $perPage,
            currentPage: $currentPage,
            options: [
                'path' => '/inicio',
            ]
        );

        return view('main.dashboard', [
            'titles'               => $userTitles->get('titles'), 
            'totalizers'           => $userTitles->get('totalizers'),
            'userAllStocks'        => $paginator,
            'userStockstotalizers' => $userStocks->get('totalizers'),
        ]);
    }
}
