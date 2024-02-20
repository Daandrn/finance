<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TittleController;
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

/* Ainda falta as views e terminar o Controller
Route::middleware('auth')->group(function () {
    Route::delete('/inicio/titulo/{id}', [TittleController::class, 'destroy'])->name('tittles.destroy');
    Route::put('/inicio/titulo/{id}', [TittleController::class, 'update'])->name('tittles.update');
    Route::get('/inicio/titulo/{id}/alterar', [TittleController::class, 'edit'])->name('tittles.edit');
    Route::get('/inicio/titulo/novo', [TittleController::class, 'create'])->name('tittles.create');
    Route::get('/inicio/titulo/{id}', [TittleController::class, 'show'])->name('tittles.show');
    Route::get('/inicio/titulo', [TittleController::class, 'index'])->name('tittles');
});*/

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/administrador', function () {
    return view('administrator.administrator');
})->middleware(['auth', 'admin'])->name('administrator');

Route::get('/inicio', function () {
    return view('main.dashboard');
})->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';
