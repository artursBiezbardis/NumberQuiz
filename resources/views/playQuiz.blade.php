@extends('layouts.app')
@section('content')
    <div><h3>{{$questionNumber}}.{{$question}}</h3></div>
    <div class="answers_container">
        <form method="POST" action="{{ route('storeAnswer') }}">
            @csrf
            @foreach($answers as $key=>$answer)
                <div>
                    <input type="radio" name="answer"
                           id="{{$answer}}" value="{{$answer}}">
                    <label class="" for="{{$answer}}">
                        {{$answer}}
                    </label>
                </div>
            @endforeach
            @error('answer')
            <p class="validation" style="position: absolute; margin-top: -5px; color: red">{{ $message }}</p>
            @enderror
            <div>
                <button type="submit">
                    Submit
                </button>
            </div>
        </form>
    </div>
@endsection
