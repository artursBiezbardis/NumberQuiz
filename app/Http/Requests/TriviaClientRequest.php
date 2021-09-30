<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TriviaClientRequest extends FormRequest
{
    protected $redirectRoute = 'playerGameStatus';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'question' => 'string|unique:quiz_results',
            'correctAnswer' => 'required|int'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'question' => (string)$this->old('question'),
            'correctAnswer' => (int)$this->old('correct_answer')
        ]);
    }

}
