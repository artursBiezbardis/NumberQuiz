<?php
declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\TriviaClientRequest;
use App\Repositories\GeneralQueryRepository;
use App\Repositories\TriviaClientRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TriviaClientService
{
    const POSSIBLE_ANSWER_COUNT = 3;

    private TriviaClientRepository $repository;
    private GeneralQueryRepository $generalRepository;

    public function __construct(TriviaClientRepository $repository, GeneralQueryRepository $generalRepository)
    {
        $this->repository = $repository;
        $this->generalRepository = $generalRepository;
    }

    public function createQuestion(TriviaClientRequest $request): RedirectResponse
    {
        $token = $request->session()->token();
        $question = $request->input('question');
        $correctAnswer = (int)$request->input('correctAnswer');
        $questionCount = $this->generalRepository->countSessionEntries($token) + 1 ?? 1;
        $this->repository->createNewQuestion($token, $questionCount, $question, $correctAnswer);
        $answers = self::generateAnswers($correctAnswer, self::POSSIBLE_ANSWER_COUNT);
        $question = ['answers' => $answers, 'question' => $question, 'questionNumber' => $questionCount];
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

    public function retrieveQuizData(Request $request): RedirectResponse
    {
        $quizData = $this->repository->getQuizData();
        $data = json_decode($quizData, true);
        $data['text'] = substr_replace($data['text'], '?', -1, 1);
        $formatData['question'] = explode(' ', $data['text']);
        $formatData['question'][0] = 'What';
        $formatData['question'] = implode(' ', $formatData['question']);
        $formatData['correct_answer'] = $data['number'];
        $request->merge($formatData);
        $request->flash();

        return redirect()->route('createQuestion');
    }
}
