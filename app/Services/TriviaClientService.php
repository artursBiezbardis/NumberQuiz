<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\GeneralQueryRepositoryInterface;
use App\Repositories\TriviaClientRepositoryInterface;
use App\Repositories\TriviaClientRepository;

class TriviaClientService
{
    const POSSIBLE_ANSWER_COUNT = 3;

    private TriviaClientRepository $clientRepository;
    private GeneralQueryRepositoryInterface $generalRepository;
    private TriviaClientRepositoryInterface $repository;

    public function __construct(
        TriviaClientRepository $clientRepository,
        TriviaClientRepositoryInterface $repository,
        GeneralQueryRepositoryInterface $generalRepository
    )
    {
        $this->repository = $repository;
        $this->clientRepository = $clientRepository;
        $this->generalRepository = $generalRepository;
    }

    public function createQuestion(string $token, string $question, int $correctAnswer): array
    {
        $questionCount = $this->generalRepository->countSessionEntries($token) + 1 ?? 1;
        $this->repository->createNewQuestion(
            $token,
            $questionCount,
            $question,
            $correctAnswer
        );
        $answers = self::generateAnswers($correctAnswer, self::POSSIBLE_ANSWER_COUNT);

        return ['answers' => $answers, 'question' => $question, 'questionNumber' => $questionCount];
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

    public function retrieveQuizData(): array
    {
        $quizData = $this->clientRepository->getQuizData();
        $data = json_decode($quizData, true);
        $data['text'] = substr_replace($data['text'], '?', -1, 1);
        $formatData['question'] = explode(' ', $data['text']);
        $formatData['question'][0] = 'What';
        $formatData['question'] = implode(' ', $formatData['question']);
        $formatData['correct_answer'] = $data['number'];

        return $formatData;
    }
}
