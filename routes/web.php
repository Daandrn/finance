<?php

use App\Http\Controllers\ProfileController;
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
    return view('teste');    
})->middleware();

Route::get("/", function () {
    return view("auth.login");
});

Route::middleware(['auth', 'admin'])->group(function () {//Adicionar a validação de ADM do middleware
    Route::delete('/administrador/usuarios/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::put('/administrador/usuarios/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/administrador/usuarios/{id}/alterar', [UserController::class, 'edit'])->name('user.edit');
    //Route::get('/administrador/usuarios/novo', [UserController::class, 'create'])->name('user.create');//Não está sendo usado, usar
    //Route::get('/administrador/usuarios/{id}', [UserController::class, 'show'])->name('user.show');//Não está sendo usado, usar
    Route::get('/administrador/usuarios', [UserController::class, 'index'])->name('users');
});

Route::middleware('auth')->group(function () {
    Route::delete('/inicio/titulo/{Title:id}', [TitleController::class, 'destroy'])->name('Titles.destroy');
    Route::put('/inicio/titulo/{id}', [TitleController::class, 'update'])->name('Titles.update');
    Route::get('/inicio/titulo/{Title:id}/alterar', [TitleController::class, 'edit'])->name('Titles.edit');
    Route::get('/inicio/titulo/novo', [TitleController::class, 'create'])->name('Titles.create');
    Route::get('/inicio/titulo/{id}', [TitleController::class, 'show'])->name('Titles.show');
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
