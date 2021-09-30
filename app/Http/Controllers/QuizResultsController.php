<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\QuizResultsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\QuizResultRequest;

class QuizResultsController extends Controller
{

    private QuizResultsService $service;

    public function __construct(QuizResultsService $service)
    {
        $this->service = $service;
    }

    public function storeAnswer(QuizResultRequest $request): RedirectResponse
    {
        return $this->service->storeAnswer($request);
    }

    public function playerGameStatus(Request $request)
    {
        return $this->service->playerGameStatus($request);
    }
}
