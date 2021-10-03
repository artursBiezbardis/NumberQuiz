<?php
declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class TriviaClientRepository
{
    const TRIVIA_URL = 'http://numbersapi.com/random/trivia?json';

    public function getQuizData(): string
    {
        return Http::get(self::TRIVIA_URL)->body();
    }

}
