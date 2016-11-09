@extends('layouts.app')

@section('header_left')
    <a href="#" class="logo"><span class="icon-logo"></span> Booksharing</a>
@endsection

@section('content')
    <div class="flex-container flex-center">
        <div class="text-center">
            <h1 class="call-to-action">Have you read...</h1>

            @if($randomBooks)
                <div class="row featured-books">
                    @foreach($randomBooks as $book)
                        <div class="col-xs-3">
                            <a href="{{route('book.view', $book->id)}}">
                                <img class="bookcover" src="{!! $book->image !!}" alt="{{$book->title}}">
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <div>
                <a href="{{route('book.add')}}" class="btn btn-lg btn-primary">Add book</a>
                <div class="clearfix visible-xs-block"></div>
                <a href="{{route('library')}}" class="btn btn-lg btn-primary">View library</a>
            </div>
        </div>
    </div>
@endsection
