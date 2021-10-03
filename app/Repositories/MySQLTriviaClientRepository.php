<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\QuizResult;

class MySQLTriviaClientRepository implements TriviaClientRepositoryInterface
{
    private QuizResult $query;
    private GeneralQueryRepositoryInterface $generalQuery;

    public function __construct(QuizResult $query, GeneralQueryRepositoryInterface $generalQuery)
    {
        $this->query = $query;
        $this->generalQuery = $generalQuery;
    }

    public function createNewQuestion(string $token, int $questionCount, string $question, int $correctAnswer): void
    {
        $this->query->create(
            [
                'session_token' => $token,
                'question_count' => $questionCount,
                'question' => $question,
                'correct_answer' => $correctAnswer
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
