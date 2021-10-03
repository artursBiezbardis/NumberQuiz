<?php
declare(strict_types=1);

namespace App\Repositories;

class MySQLQuizResultsRepository implements QuizResultsRepositoryInterface
{

    private GeneralQueryRepositoryInterface $generalQueries;

    public function __construct(GeneralQueryRepositoryInterface $generalQueries)
    {
        $this->generalQueries = $generalQueries;
    }

    public function storeAnswer(string $token, int $answer): void
    {
        $this->generalQueries->getSessionResults($token)
            ->where('question_count', $this->generalQueries->countSessionEntries($token))
            ->update(['session_answer' => $answer]);
    }

    public function answers(string $token): array
    {
        return $this->generalQueries->getSessionResults($token)
            ->where('question_count', $this->generalQueries->countSessionEntries($token))
            ->get(['correct_answer', 'session_answer'])
            ->toArray();
    }

    public function getLastCorrectAnswer(string $token): array
    {
        return $this->generalQueries->getSessionResults($token)
            ->where('question_count', $this->generalQueries->countSessionEntries($token) - 1)
            ->get(['correct_answer', 'question', 'question_count'])->toArray();
    }

    public function deleteSessionResults(string $token): void
    {
        $this->generalQueries->getSessionResults($token)->delete();
    }

    public function checkIfLastAnswerIsAnswered(string $token): bool
    {
        $getVal = $this->generalQueries->getSessionResults($token)
            ->where('question_count', $this->generalQueries->countSessionEntries($token));
        $sessionAnswer = $getVal->get('session_answer')->toArray()[0]['session_answer'];

        return $sessionAnswer !== null;
    }
}
