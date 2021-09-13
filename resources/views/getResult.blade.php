@extends('layouts.app')
@section('content')
    <div>
        @if($result)
            <div><h1 style="color: gold">You WIN!!!</h1></div>
        @else
            <div><h1 style="color: red">You LOST!!!</h1></div>
            @if(empty($lastCorrectAnswer))
                <div><h3></h3>You answered correctly on 0 out of {{$questionsToAnswer}} questions.</div>
            @else
                <div><h3></h3>You answered correctly on {{$lastCorrectAnswer[0]['question_count']}} out
                    of {{$questionsToAnswer}} questions.
                </div>
                <div><h3>Last question you answered correctly is: {{$lastCorrectAnswer[0]['question']}} </h3></div>
                <div><h3>Answer is {{$lastCorrectAnswer[0]['correct_answer']}} </h3></div>
            @endif
        @endif
    </div>
    <div>
        <form action="{{ route('playerGameStatus') }}">
            @csrf
            <button class="button" type="submit">Play again</button>
        </form>
    </div>
@endsection
