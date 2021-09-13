<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\QuestionTrait;
use App\Models\QuizResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @mixin Builder
 */
class QuizPlayController extends Controller
{
    use QuestionTrait;

    private QuizResult $result;

    public function __construct(QuizResult $result)
    {
        $this->result = $result;
    }

    protected function createQuestion(Request $request)
    {
        $resultQuery = $this->result;
        /*do{$question = self::extractQuestionDataForDB();}
        while()*/
        $question = self::extractQuestionDataForDB();
        $resultQuery->create([
            'session_token' => $request->session()->get('_token'),
            'question_count' => $resultQuery
                ->sessionExist($request) ? $resultQuery->countSessionEntries($request) + 1 : 1,
            'question' => $question['question'],
            'correct_answer' => $question['answer']
        ]);
        $answers = self::generateAnswers($question['answer'], 4);

        return view('playQuiz')
            ->with('question', $question['question'])
            ->with('answers', $answers);
    }
}
