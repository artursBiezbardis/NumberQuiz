<?php

namespace App\Repositories;

interface GeneralQueryRepositoryInterface
{
    public function countSessionEntries(string $token): int;

    public function getSessionResults(string $token);

    public function sessionExistInDB(string $token): bool;
}
