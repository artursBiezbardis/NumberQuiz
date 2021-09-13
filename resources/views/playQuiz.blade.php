@extends('layouts.app')
@section('content')
    <div><h3>{{$question}}</h3>
        <div>
            <form method="POST" action="{{ route('storeAnswer') }}">
                @csrf
                @foreach($answers as $key=>$answer)
                    <div class="">
                        <input class="" type="radio" name="answer"
                               id="{{$answer}}" value="{{$answer}}">
                        <label class="form-check-label" for="{{$answer}}">
                            {{$answer}}
                        </label>
                    </div>
                @endforeach
                @error('answer')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group row mb-0">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>

@endsection
