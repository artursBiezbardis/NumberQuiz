<?php

namespace App\Repositories;

interface QuizResultsRepositoryInterface
{
    public function storeAnswer(string $token, int $answer): void;

    public function answers(string $token): array;

    public function getLastCorrectAnswer(string $token): array;

    public function deleteSessionResults(string $token): void;

    public function checkIfLastAnswerIsAnswered(string $token): bool;
}
