<?php

namespace App\Repositories;

use App\Models\QuizResult;

class QuizResultsRepository
{

    private GeneralQueryRepository $generalQueries;

    public function __construct(GeneralQueryRepository $generalQueries)
    {

        $this->generalQueries = $generalQueries;
    }

    public function updateAnswer(string $token, int $answer): void
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

}
