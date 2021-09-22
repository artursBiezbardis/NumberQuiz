<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Services\TriviaClientService;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Http;

class TriviaClientRepository
{
    const TRIVIA_URL = 'http://numbersapi.com/random/trivia?json';

    private QuizResult $query;

    public function __construct(QuizResult $query)
    {
        $this->query = $query;
    }


    public function getQuizData():string
    {
        return Http::get(self::TRIVIA_URL)->body();
    }

    public function createNewQuestion(string $token,int $questionCount,array $question ):void
    {
        $this->query->create(
            [
                'session_token' => $token,
                'question_count' => $questionCount,
                'question' => $question['question'],
                'correct_answer' => $question['correct_answer']
            ]
        );
    }

    public function getSessionResults(string $token)
    {
        return $this->query->where('session_token', $token);
    }

    public function checkIfUniqueQuestion( $token, array $question): bool
    {
        return self::getSessionResults($token)
            ->where('question', $question['question'])
            ->exists();
    }

    public function countSessionEntries(string $token): int
    {
        return self::getSessionResults($token)->count();
    }
}
