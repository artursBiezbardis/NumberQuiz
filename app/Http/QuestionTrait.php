<?php
declare(strict_types=1);

namespace App\Http;

trait QuestionTrait
{
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

    public function formatClientData($clientData):array
    {
        $data = json_decode($clientData, true);
        $data['text'] = substr_replace($data['text'], '?', -1, 1);
        $formatData['question'] = explode(' ', $data['text']);
        $formatData['question'][0] = 'What';
        $formatData['question'] = implode(' ', $formatData['question']);
        $formatData['correct_answer'] = $data['number'];

        return $formatData;
    }
}
