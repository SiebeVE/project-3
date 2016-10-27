@extends('layouts.app')

@section('content')

    <div class="row">
        @foreach($books as $book)
            <img src="{{$book->image}}" alt="{{$book->title}}" width="200px">
        @endforeach
    </div>

@endsection
