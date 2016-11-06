@extends('layouts.app')

@section('header_left')
    <a href="/">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
        Home
    </a>
@endsection

@section('content')
    <div class="flex-container flex-center text-center big-msg">
        <div>
            <i class="fa fa-check-circle fa-lg"></i>
            <h1>Book added!</h1>
            <a href="{{route('library')}}" class="btn btn-lg btn-default">Go to the library</a>
            <a href="{{route('book.add')}}" class="btn btn-lg btn-default">Add another book</a>
        </div>

    </div>

    <script>
        $('.site-pusher').addClass('brandColorGreenBackground').css('background-image', 'none');
    </script>
@endsection