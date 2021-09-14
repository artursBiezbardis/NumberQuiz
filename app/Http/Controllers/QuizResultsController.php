<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class QuizResultsController extends Controller
{
    const MAX_AMOUNT_OF_QUESTIONS = 20;

    private QuizResult $result;

    public function __construct(QuizResult $result)
    {
        $this->result = $result;
    }

    public function storeAnswer(Request $request): RedirectResponse
    {
        $request->validate(['answer' => 'required|int']);
        $this->result->updateAnswer($request);

        return redirect()->route('playerGameStatus');
    }

    public function playerGameStatus(Request $request)
    {
        $questionCount = $this->result->countSessionEntries($request) ?? 0;
        $answerResult = self::compareAnswer($request);

        if ($questionCount >= self::MAX_AMOUNT_OF_QUESTIONS && $answerResult) {
            $this->result->getSessionResults($request)->delete();

            return view('getResult')
                ->with('result', true);
        }
        if (!$answerResult && $questionCount != 0) {
            $lastCorrectAnswer = $this->result->getSessionResults($request)
                ->where('question_count', $this->result->countSessionEntries($request) - 1)
                ->get(['correct_answer', 'question', 'question_count'])->toArray();
            $this->result->getSessionResults($request)->delete();

            return view('getResult')
                ->with('result', false)
                ->with('lastCorrectAnswer', $lastCorrectAnswer)
                ->with('questionsToAnswer', self::MAX_AMOUNT_OF_QUESTIONS);
        }

        return redirect()->route('createQuestion');
    }

    public function compareAnswer(Request $request): bool
    {
        $lastQuestionResults = $this->result->getSessionResults($request)
            ->where('question_count', $this->result->countSessionEntries($request))
            ->get(['correct_answer', 'session_answer'])->toArray();
        $correctAnswer = $lastQuestionResults[0]['correct_answer'] ?? 0;
        $sessionAnswer = $lastQuestionResults[0]['session_answer'] ?? 1;

        return $correctAnswer == $sessionAnswer;
    }

}
