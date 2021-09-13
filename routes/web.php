<?php

use App\Http\Controllers\QuizPlayController;
use App\Http\Controllers\QuizResultsController;
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

Route::view('/','startQuiz');

Route::view('/playQuiz','playQuiz')
    ->name('playQuiz');

Route::post('/storeAnswer', [QuizResultsController::class,'storeAnswer'])
    ->name('storeAnswer');

Route::get('/playerGameStatus', [QuizResultsController::class,'playerGameStatus'])
    ->name('playerGameStatus');

Route::get('/createQuestion',[QuizPlayController::class,'createQuestion'])
    ->name('createQuestion');

Route::get('/getQuestion',[\App\Http\Controllers\TriviaClientController::class, 'getRandomQuestion'])
    ->name('getQuestion');
