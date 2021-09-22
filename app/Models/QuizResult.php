<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @mixin Builder
 */
class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'question_count',
        'question',
        'correct_answer',
        'session_answer'
    ];
}
