<?php

use App\Http\Controllers\ProfileController;
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

Route::get("/", function () {
    return view("auth.login");
});

Route::middleware('auth')->group(function () {
    Route::delete('/administrador/usuarios/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::put('/administrador/usuarios/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/administrador/usuarios/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::get('/administrador/usuarios', [UserController::class, 'index'])->name('users');
});

Route::get('/administrador', function () {
    return view('administrator.administrator');
})->middleware(['auth'])->name('administrator');

Route::get('/inicio', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
