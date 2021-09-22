<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\QuizResult;
use Illuminate\Support\Facades\Http;

class TriviaClientRepository
{
    const TRIVIA_URL = 'http://numbersapi.com/random/trivia?json';
    private QuizResult $query;
    private GeneralQueryRepository $generalQuery;

    public function __construct(QuizResult $query, GeneralQueryRepository $generalQuery)
    {
        $this->query = $query;
        $this->generalQuery = $generalQuery;
    }

    public function getQuizData(): string
    {
        return Http::get(self::TRIVIA_URL)->body();
    }

    public function createNewQuestion(string $token, int $questionCount, array $question): void
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

    public function checkIfUniqueQuestion($token, array $question): bool
    {
        return $this->generalQuery->getSessionResults($token)
            ->where('question', $question['question'])
            ->exists();
    }

}
