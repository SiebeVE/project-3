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
            <h1>Sharing is caring! &hearts;</h1>
            <h2>Book added.</h2>
            <p>You'll receive a notification when someone's interested in your book.</p>
            <p>Want to manage your books? You can find them <a href="{{route('book.index')}}">here</a>.</p>
            <a href="{{route('library')}}" class="btn btn-lg btn-default">Go to the library</a>
            <a href="{{route('book.add')}}" class="btn btn-lg btn-default">Add another book</a>
        </div>

    </div>

    <script>
        $('.site-pusher').addClass('brandColorBackground').css('background-image', 'none');
    </script>
@endsection