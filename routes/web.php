<?php

use App\Http\Controllers\CarController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/car', [CarController::class, 'index'])->name('car.index');
Route::get('/car/{id}', [CarController::class, 'show'])->name('car.show');
Route::post('/car', [CarController::class, 'store'])->name('car.store');
Route::put('/car/{id}', [CarController::class, 'update'])->name('car.update');
Route::delete('/car/{id}', [CarController::class, 'destroy'])->name('car.destroy');