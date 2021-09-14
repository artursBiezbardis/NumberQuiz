<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @mixin \Eloquent
 * @mixin Builder
 */
class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'question_count',
        'question',
        'correct_answer',
        'session_answer'
    ];

    public function sessionExist(Request $request): bool
    {
        return self::getSessionResults($request)->exists();
    }

    public function getSessionResults(Request $request)
    {
        return self::where('session_token', ($request->session()->get('_token')));
    }

    public function sessionGames(Request $request): array
    {
        return self::getSessionResults($request)
            ->orderBy('question_count')
            ->get()
            ->toArray();
    }

    public function updateAnswer(Request $request): void
    {
        self::getSessionResults($request)
            ->where('question_count', self::countSessionEntries($request))
            ->update(['session_answer' => $request->input('answer')]);
    }

    public function countSessionEntries(Request $request): int
    {
        return self::getSessionResults($request)->count();
    }

    public function checkIfUniqueQuestion(Request $request, array $question): bool
    {
        return self::getSessionResults($request)
            ->where('question', $question['question'])
            ->exists();
    }
}
