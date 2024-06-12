<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/info', function () {
    return phpinfo();
});
Route::get('/user', [UserController::class, 'index'])->name('user');
Route::post('/user/edit', [UserController::class, 'edit'])->name('user.edit');
Route::post('/user/upsert', [UserController::class, 'upsert'])->name('user.upsert');
