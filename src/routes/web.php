<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalcController;
use App\Http\Controllers\TaskController;
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

//Route::get('/', function () {
//    return view('calculator');
//});

Route::resource('tasks', TaskController::class);

Route::get('/list', [\App\Http\Controllers\TodoListController::class, 'index']);
Route::get('/', [CalcController::class, 'index'])->name('calculator.home');
Route::get('/process', [CalcController::class, 'calculate'])->name('calculator.process');
