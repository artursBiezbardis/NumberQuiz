<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\GeneralQueryRepositoryInterface;
use App\Services\QuizResultsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\QuizResultRequest;
use App\Repositories\QuizResultsRepositoryInterface;

class QuizResultsController extends Controller
{
    const MAX_AMOUNT_OF_QUESTIONS = 3;
    private QuizResultsService $service;
    private QuizResultsRepositoryInterface $repository;
    private GeneralQueryRepositoryInterface $generalRepository;

    public function __construct(
        QuizResultsService              $service,
        QuizResultsRepositoryInterface  $repository,
        GeneralQueryRepositoryInterface $generalRepository
    )
    {
        $this->service = $service;
        $this->repository = $repository;
        $this->generalRepository = $generalRepository;
    }

    public function storeAnswer(QuizResultRequest $request): RedirectResponse
    {
        $answer = (int)$request->validated()['answer'];
        $this->repository->storeAnswer($request->session()->token(), $answer);

        return redirect()->route('playerGameStatus');
    }

    public function playerGameStatus(Request $request)
    {
        $token = $request->session()->token();
        $sessionExist = $this->generalRepository->sessionExistInDB($token);
        $questionCount = $this->generalRepository->countSessionEntries($token) ?? 0;
        $lastAnswerIsAnswered = $questionCount == 0 ? false :
            $this->repository->checkIfLastAnswerIsAnswered($token);
        $answerResult = $this->service->compareAnswer($token);

        if ($sessionExist && $lastAnswerIsAnswered && $questionCount >= self::MAX_AMOUNT_OF_QUESTIONS && $answerResult) {
            $this->repository->deleteSessionResults($token);

            return view('getResult')
                ->with('result', true);
        }
        if ($sessionExist && $lastAnswerIsAnswered && !$answerResult && $questionCount !== 0) {
            $lastCorrectAnswer = $this->repository->getLastCorrectAnswer($token);
            $this->repository->deleteSessionResults($token);

            return view('getResult')
                ->with('result', false)
                ->with('lastCorrectAnswer', $lastCorrectAnswer)
                ->with('questionsToAnswer', self::MAX_AMOUNT_OF_QUESTIONS);
        }
        if ($sessionExist && !$lastAnswerIsAnswered && $questionCount != 1) {

            return redirect()->back();
        }
        if ($sessionExist && !$lastAnswerIsAnswered && $questionCount == 1) {

            return redirect()->route('playQuiz');
        }

        return redirect()->route('retrieveQuizData');
    }
}
