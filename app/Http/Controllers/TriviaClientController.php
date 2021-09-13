<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TriviaClientController extends Controller
{
    const TRIVIA_URL='http://numbersapi.com/random/trivia?json';

    public function getRandomQuestion()
    {
        $response=Http::get(self::TRIVIA_URL);
        return $response;
    }
}
