<?php

use App\Http\Controllers\{
    DashBoardController,
    ModalityController,
    ProfileController,
    StocksController,
    TitleController,
    UserController,
    UserStocksController,
    UserStocksMovementController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('teste', function () {
    return include __DIR__.'/../tests/Teste.php';    
})->middleware('auth');

Route::get("/", function () {
    return view("auth.login");
});

Route::middleware(['auth', 'admin'])->group(function () {//Adicionar a validação de ADM do middleware
    Route::delete('/administrador/usuarios/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::put('/administrador/usuarios/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/administrador/usuarios/{id}/alterar', [UserController::class, 'edit'])->name('user.edit');
    //Route::post('/administrador/usuarios/novo', [UserController::class, 'create'])->name('user.create');//Não está sendo usado, usar
    //Route::get('/administrador/usuarios/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/administrador/usuarios', [UserController::class, 'index'])->name('users');

    Route::delete('/administrador/modalidades/{id}', [ModalityController::class, 'destroy'])->name('modality.destroy');
    Route::post('/administrador/modalidade/store', [ModalityController::class, 'store'])->name('modality.store');
    Route::put('/administrador/modalidades/{id}', [ModalityController::class, 'update'])->name('modality.update');
    Route::get('/administrador/modalidades/{id}/alterar', [ModalityController::class, 'edit'])->name('modality.edit');
    Route::get('/administrador/modalidades', [ModalityController::class, 'index'])->name('modalities');

    Route::delete('/administrador/acoes/{id}', [StocksController::class, 'destroy'])->name('stocks.destroy');
    Route::post('/administrador/acao/store', [StocksController::class, 'store'])->name('stocks.store');
    Route::put('/administrador/acoes/{id}', [StocksController::class, 'update'])->name('stocks.update');
    Route::get('/administrador/acoes/atualizarValores', [StocksController::class, 'updateStocksValues'])->name('userStocks.updateValues');
    Route::get('/administrador/acoes/{id}/alterar', [StocksController::class, 'edit'])->name('stocks.edit');
    Route::get('/administrador/acoes', [StocksController::class, 'index'])->name('stocks');
});

Route::middleware('auth')->group(function () {
    Route::get('/inicio', [DashBoardController::class, 'index'])->name('dashboard');

    Route::delete('/titulo/{id}', [TitleController::class, 'destroy'])->name('titles.destroy');
    Route::put('/titulo/{title}', [TitleController::class, 'update'])->name('titles.update');
    Route::post('/titulo/importar', [TitleController::class, 'import'])->name('titles.import');
    Route::post('/titulo', [TitleController::class, 'store'])->name('titles.store');
    Route::get('/titulo/novo', [TitleController::class, 'create'])->name('titles.create');
    Route::get('/titulo/{title}/alterar', [TitleController::class, 'edit'])->name('titles.edit');
    Route::get('/titulo/{id}', [TitleController::class, 'show'])->name('titles.show');

    Route::delete('/acoes/{id}', [UserStocksController::class, 'destroy'])->name('userStocks.destroy');
    Route::put('/acoes/{userStocks}', [UserStocksController::class, 'update'])->name('userStocks.update');
    Route::post('/acoes/importar', [UserStocksController::class, 'import'])->name('userStocks.import');
    Route::post('/acoes', [UserStocksController::class, 'store'])->name('userStocks.store');
    Route::get('/acoes/novo', [UserStocksController::class, 'create'])->name('userStocks.create');
    Route::get('/acoes/{userStocks}/alterar', [UserStocksController::class, 'edit'])->name('userStocks.edit');
    Route::get('/acoes/{id}', [UserStocksController::class, 'show'])->name('userStocks.show');

    Route::delete('/acoes/movimentacao/{id}', [UserStocksMovementController::class, 'destroy'])->name('userStocksMovement.destroy');
    Route::put('/acoes/movimentacao/{userStocksMovement}', [UserStocksMovementController::class, 'update'])->name('userStocksMovement.update');
    Route::post('/acoes/movimentacao/importar', [UserStocksMovementController::class, 'import'])->name('userStocksMovement.import');
    Route::post('/acoes/movimentacao', [UserStocksMovementController::class, 'store'])->name('userStocksMovement.store');
    Route::get('/acoes/movimentacao/novo/{id?}', [UserStocksMovementController::class, 'create'])->name('userStocksMovement.create');
    Route::get('/acoes/movimentacao/{userStocksMovement}/alterar', [UserStocksMovementController::class, 'edit'])->name('userStocksMovement.edit');
});

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/administrador', function () {
    return view('administrator.administrator');
})->middleware(['auth', 'admin'])->name('administrator');

/*Route::get('/inicio', function () {
    return view('main.dashboard');
})->middleware(['auth'])->name('dashboard');*/


require __DIR__.'/auth.php';
