<?php

use App\Http\Controllers\IdeaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index');
Route::get('/ideas/{idea:slug}', [IdeaController::class, 'show'])->name('ideas.show');

require __DIR__.'/auth.php';
