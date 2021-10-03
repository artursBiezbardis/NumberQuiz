<?php

namespace App\Repositories;

interface TriviaClientRepositoryInterface
{
    public function createNewQuestion(string $token, int $questionCount, string $question, int $correctAnswer): void;

    public function checkIfUniqueQuestion($token, array $question): bool;

}
