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


Route::get('/', [QuizResultsController::class, 'playerGameStatus'])
    ->name('playerGameStatus');

Route::view('/playQuiz', 'playQuiz');
Route::get('/playQuiz', [TriviaClientController::class, 'playQuiz'])
    ->name('playQuiz');

Route::get('/getQuestion', [TriviaClientController::class, 'getRandomQuestion'])
    ->name('getQuestion');

Route::get('/createQuestion', [TriviaClientController::class, 'createQuestion'])
    ->name('createQuestion');

Route::get('/retrieveQuizData', [TriviaClientController::class, 'retrieveQuizData'])
    ->name('retrieveQuizData');

Route::post('/storeAnswer', [QuizResultsController::class, 'storeAnswer'])
    ->name('storeAnswer');
