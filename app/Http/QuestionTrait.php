<?php
declare(strict_types=1);

namespace App\Http;

use Illuminate\Support\Facades\Http;

trait QuestionTrait
{

    public function extractQuestionDataForDB(): array
    {
        do {
            $getQuestionNumber = 'random/trivia';
            $triviaUrl = 'http://numbersapi.com/' . $getQuestionNumber . '?json';
            $data = json_decode(Http::get($triviaUrl)->body(), true);
            $data['text'] = substr_replace($data['text'], '?', -1, 1);
            $extractData['question'] = explode(' ', $data['text']);
            $extractData['question'][0] = 'What';
            $extractData['question'] = implode(' ', $extractData['question']);
            $extractData['answer'] = $data['number'];
        } while (is_float($extractData['answer']) && empty($extractData['answer']) /*&& $compareAnsweredQuestions*/);

        return $extractData;
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
