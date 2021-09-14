<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\QuestionTrait;
use App\Models\QuizResult;
use App\Rules\ValidQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class TriviaClientController extends Controller
{
    use QuestionTrait;

    const TRIVIA_URL = 'http://numbersapi.com/random/trivia?json';
    const POSSIBLE_ANSWER_COUNT = 4;

    private QuizResult $result;


    public function __construct(QuizResult $result)
    {
        $this->result = $result;
    }

    public function createQuestion(Request $request): RedirectResponse
    {
        $question = self::getRandomQuestion();
        $questionUnique = $this->result->checkIfUniqueQuestion($request, $question);
        $validate = Validator::make($question, [
                'question' => ['required', 'string', new ValidQuestion($questionUnique)],
                'correct_answer' => 'required|int'
            ]
        );
        if ($validate->fails()) {

            return redirect()->route('createQuestion');
        }
        $resultQuery = $this->result;
        $questionNumber = $this->result->countSessionEntries($request) + 1 ?? 1;
        $resultQuery->create([
            'session_token' => $request->session()->get('_token'),
            'question_count' => $questionNumber,
            'question' => $question['question'],
            'correct_answer' => $question['correct_answer']
        ]);
        $answers = self::generateAnswers($question['correct_answer'], self::POSSIBLE_ANSWER_COUNT);
        $question = ['answers' => $answers, 'question' => $question['question'], 'questionNumber'=>$questionNumber];
        $request->session()->put('question', $question);

        return redirect()->route('playQuiz');
    }

    public function getRandomQuestion(): array
    {
        return self::formatClientData(Http::get(self::TRIVIA_URL)->body());
    }

    public function playQuiz(Request $request)
    {
        $question = $request->session()->get('question');

        return view('playQuiz')
            ->with('question', $question['question'])
            ->with('answers', $question['answers'])
            ->with('questionNumber', $question['questionNumber']);
    }
}
