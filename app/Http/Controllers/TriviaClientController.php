<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TriviaClientRequest;
use App\Services\TriviaClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TriviaClientController extends Controller
{
    private TriviaClientService $service;

    public function __construct(TriviaClientService $service)
    {
        $this->service = $service;
    }

    public function retrieveQuizData(Request $request): RedirectResponse
    {
        return $this->service->retrieveQuizData($request);
    }

    public function createQuestion(TriviaClientRequest $request): RedirectResponse
    {
        return $this->service->createQuestion($request);
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
