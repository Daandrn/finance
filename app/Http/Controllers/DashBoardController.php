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
        $userAllTitles = $userTitles->get('titles');

        //Paginator titles
        $currentPageTitles = $request->get('titlespage', 1);
        $titlesPerPage = 10; 
        $totalTitles = count($userAllTitles); 

        $titlesForCurrentPage = $userAllTitles->slice(($currentPageTitles - 1) * $titlesPerPage, $titlesPerPage)->values();

        $paginatorTitles = new LengthAwarePaginator(
            items: $titlesForCurrentPage,
            total: $totalTitles,
            perPage: $titlesPerPage,
            currentPage: $currentPageTitles,
            options: [
                'path' => '/inicio',
            ]
        );

        $paginatorTitles->setPageName('titlespage');
        /*************************************/
        
        $userStocks = $this->userStocksController->getUserStocks();
        $userAllStocks = $userStocks->get('userAllStocks');

        //Paginator stocks
        $currentPageStocks = $request->get('stockspage', 1);
        $stocksPerPage = 10; 
        $totalStocks = count($userAllStocks); 

        $stocksForCurrentPage = $userAllStocks->slice(($currentPageStocks - 1) * $stocksPerPage, $stocksPerPage)->values();

        $paginatorStocks = new LengthAwarePaginator(
            items: $stocksForCurrentPage,
            total: $totalStocks,
            perPage: $stocksPerPage,
            currentPage: $currentPageStocks,
            options: [
                'path' => '/inicio',
            ]
        );

        $paginatorStocks->setPageName('stockspage');
        /*************************************/

        return view('main.dashboard', [
            'titles'               => $paginatorTitles, 
            'totalizers'           => $userTitles->get('totalizers'),
            'userAllStocks'        => $paginatorStocks,
            'userStockstotalizers' => $userStocks->get('totalizers'),
        ]);
    }
}
