<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\QuizResult;
use Illuminate\Database\Eloquent\Builder;

class GeneralQueryRepository
{
    private QuizResult $query;

    public function __construct(QuizResult $query)
    {

        $this->query = $query;
    }

    public function countSessionEntries(string $token): int
    {
        return self::getSessionResults($token)->count();
    }

    public function getSessionResults(string $token): Builder
    {
        return $this->query->where('session_token', $token);
    }

    public function sessionExistInDB(string $token): bool
    {
        return self::getSessionResults($token)->exists();
    }
}
