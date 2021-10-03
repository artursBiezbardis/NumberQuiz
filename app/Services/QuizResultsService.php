<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\QuizResultsRepositoryInterface;

class QuizResultsService
{
    private QuizResultsRepositoryInterface $repository;

    public function __construct(QuizResultsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function compareAnswer(string $token): bool
    {
        $lastQuestionResults = $this->repository->answers($token);
        $correctAnswer = $lastQuestionResults[0]['correct_answer'] ?? 0;
        $sessionAnswer = $lastQuestionResults[0]['session_answer'] ?? 1;

        return $correctAnswer == $sessionAnswer;
    }
}
