<?php

use App\Http\Controllers\QuizResultsController;
use App\Http\Controllers\TriviaClientController;
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

Route::view('/', 'startQuiz');

Route::view('/playQuiz', 'playQuiz');
Route::get('/playQuiz', [TriviaClientController::class, 'playQuiz'])
    ->name('playQuiz');

Route::get('/getQuestion', [TriviaClientController::class, 'getRandomQuestion'])
    ->name('getQuestion');
Route::get('/createQuestion', [TriviaClientController::class, 'createQuestion'])
    ->name('createQuestion');

Route::post('/storeAnswer', [QuizResultsController::class, 'storeAnswer'])
    ->name('storeAnswer');
Route::get('/playerGameStatus', [QuizResultsController::class, 'playerGameStatus'])
    ->name('playerGameStatus');

