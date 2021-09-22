<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\TriviaClientRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use App\Rules\ValidQuestion;
use Illuminate\Http\Request;

class TriviaClientService
{
    const TRIVIA_URL = 'http://numbersapi.com/random/trivia?json';
    const POSSIBLE_ANSWER_COUNT = 4;

    private TriviaClientRepository $repository;

    public function __construct(TriviaClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function formatQuizData():array
    {
        $quizData= $this->repository->getQuizData();
        $data = json_decode($quizData, true);
        $data['text'] = substr_replace($data['text'], '?', -1, 1);
        $formatData['question'] = explode(' ', $data['text']);
        $formatData['question'][0] = 'What';
        $formatData['question'] = implode(' ', $formatData['question']);
        $formatData['correct_answer'] = $data['number'];

        return $formatData;
    }

    public function createQuestion(Request $request): RedirectResponse
    {
        $token=$request->session()->token();
        $question = self::formatQuizData();
        $questionUnique = $this->repository->checkIfUniqueQuestion($token, $question);
        $validate = Validator::make($question, [
                'question' => ['required', 'string', new ValidQuestion($questionUnique)],
                'correct_answer' => 'required|int'
            ]
        );
        if ($validate->fails()) {

            return redirect()->route('createQuestion');
        }

        $questionCount = $this->repository->countSessionEntries($token) + 1 ?? 1;
        $this->repository->createNewQuestion($token, $questionCount, $question);
        $answers = self::generateAnswers($question['correct_answer'], self::POSSIBLE_ANSWER_COUNT);
        $question = ['answers' => $answers, 'question' => $question['question'], 'questionNumber'=>$questionCount];
        $request->session()->put('question', $question);

        return redirect()->route('playQuiz');
    }

    public function generateAnswers(int $answer, int $answerCount): array
    {
        $possibleAnswers[0] = $answer;
        $randomMultiplayer = rand(1, 5);
        for ($i = 1; $i < $answerCount; $i++) {
            $wrongAnswer = rand($answerCount + 1, ($answer * $randomMultiplayer));
            if (in_array($wrongAnswer, $possibleAnswers)) {
                $i--;
            } else {
                $possibleAnswers[$i] = $wrongAnswer;
            }
        }
        shuffle($possibleAnswers);

        return $possibleAnswers;
    }
}
