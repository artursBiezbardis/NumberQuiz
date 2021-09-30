<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\GeneralQueryRepository;
use App\Repositories\QuizResultsRepository;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\QuizResultRequest;
use Illuminate\Http\Request;

class QuizResultsService
{
    const MAX_AMOUNT_OF_QUESTIONS = 3;
    private QuizResultsRepository $repository;
    private GeneralQueryRepository $generalRepository;

    public function __construct(QuizResultsRepository $repository, GeneralQueryRepository $generalRepository)
    {
        $this->repository = $repository;
        $this->generalRepository = $generalRepository;
    }

    public function storeAnswer(QuizResultRequest $request): RedirectResponse
    {
        $token = $request->session()->token();
        $answer = (int)$request->input('answer');
        $this->repository->updateAnswer($token, $answer);

        return redirect()->route('playerGameStatus');
    }

    public function playerGameStatus(Request $request)
    {
        $token = $request->session()->token();
        $sessionExist = $this->generalRepository->sessionExistInDB($token);
        $questionCount = $this->generalRepository->countSessionEntries($token) ?? 0;
        $lastAnswerIsAnswered = $questionCount == 0 ? false : $this->repository->checkIfLastAnswerIsAnswered($token);
        $answerResult = self::compareAnswer($request);

        if ($sessionExist && $lastAnswerIsAnswered && $questionCount >= self::MAX_AMOUNT_OF_QUESTIONS && $answerResult) {
            $this->repository->deleteSessionResults($token);

            return view('getResult')
                ->with('result', true);
        }
        if ($sessionExist && !$answerResult && $questionCount !== 0) {
            $lastCorrectAnswer = $this->repository->getLastCorrectAnswer($token);
            $this->repository->deleteSessionResults($token);

            return view('getResult')
                ->with('result', false)
                ->with('lastCorrectAnswer', $lastCorrectAnswer)
                ->with('questionsToAnswer', self::MAX_AMOUNT_OF_QUESTIONS);
        }
        if ($sessionExist && !$lastAnswerIsAnswered) {

            return redirect()->back();
        }

        return redirect()->route('retrieveQuizData');
    }

    public function compareAnswer(Request $request): bool
    {
        $token = $request->session()->token();
        $lastQuestionResults = $this->repository->answers($token);
        $correctAnswer = $lastQuestionResults[0]['correct_answer'] ?? 0;
        $sessionAnswer = $lastQuestionResults[0]['session_answer'] ?? 1;

        return $correctAnswer == $sessionAnswer;
    }
}
