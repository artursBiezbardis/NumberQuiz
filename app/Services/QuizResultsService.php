<?php

namespace App\Services;

use App\Repositories\GeneralQueryRepository;
use App\Repositories\QuizResultsRepository;
use Illuminate\Http\RedirectResponse;
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

    public function storeAnswer(Request $request): RedirectResponse
    {
        $token = $request->session()->token();
        $answer = $request->input('answer');
        $request->validate(['answer' => 'required|int']);
        $this->repository->updateAnswer($token, $answer);

        return redirect()->route('playerGameStatus');
    }

    public function playerGameStatus(Request $request)
    {
        $token = $request->session()->token();
        $questionCount = $this->generalRepository->countSessionEntries($token) ?? 0;
        $answerResult = self::compareAnswer($request);

        if ($questionCount >= self::MAX_AMOUNT_OF_QUESTIONS && $answerResult) {
            $this->repository->deleteSessionResults($token);

            return view('getResult')
                ->with('result', true);
        }
        if (!$answerResult && $questionCount != 0) {
            $lastCorrectAnswer = $this->repository->getLastCorrectAnswer($token);
            $this->repository->deleteSessionResults($token);

            return view('getResult')
                ->with('result', false)
                ->with('lastCorrectAnswer', $lastCorrectAnswer)
                ->with('questionsToAnswer', self::MAX_AMOUNT_OF_QUESTIONS);
        }

        return redirect()->route('createQuestion');
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
