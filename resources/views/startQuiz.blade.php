@extends('layouts.app')
@section('content')

    <p>To start game press the button!</p>
    <form action="{{ route('playerGameStatus') }}">
        @csrf
        <button class="button" type="submit">Start quiz</button>
    </form>



@endsection
