<?php

use App\Http\Controllers\ModalityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\UserController;
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
    return include __DIR__.'/../tests/teste.php';    
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
    Route::get('/administrador/acoes/{id}/alterar', [StocksController::class, 'edit'])->name('stocks.edit');
    Route::get('/administrador/acoes', [StocksController::class, 'index'])->name('stocks');
});

Route::middleware('auth')->group(function () {
    Route::delete('/titulo/{id}', [TitleController::class, 'destroy'])->name('titles.destroy');
    Route::put('/titulo/{title}', [TitleController::class, 'update'])->name('titles.update');
    Route::post('/titulo/importar', [TitleController::class, 'import'])->name('titles.import');
    Route::post('/titulo', [TitleController::class, 'store'])->name('titles.store');
    Route::get('/titulo/novo', [TitleController::class, 'create'])->name('titles.create');
    Route::get('/titulo/{title}/alterar', [TitleController::class, 'edit'])->name('titles.edit');
    Route::get('/titulo/{id}', [TitleController::class, 'show'])->name('titles.show');
    Route::get('/inicio', [TitleController::class, 'index'])->name('dashboard');
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
