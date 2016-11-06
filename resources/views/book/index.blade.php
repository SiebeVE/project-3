@extends('layouts.app')

@section('header_left')
    <a href="/">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
        Back
    </a>
@endsection

@section('content')
    <div class="container">
        <div class="panel panel-book">
            <div class="panel-body">

                <div class="button-group pull-right">
                    <a href="{{route('book.add')}}" class="btn btn-lg btn-primary">Add a book</a>
                </div>

                <h1>My books</h1>

                <p>These are all your listed books on Bookshare.</p>

            </div>
            <div class="table-responsive">
                <table class="table table-striped mybooks">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Title & author</th>
                        <th>Status</th>
                        <th>Listing</th>
                        <th>Price</th>
                        <th>Edit</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($books)
                        @foreach($books as $book)
                            <tr>
                                <td><img src="{{$book->image}}" alt="{{$book->title}}"></td>
                                <td>
                                    <a href="{{route('book.edit', $book->pivot->id)}}">
                                        <strong>{{$book->title}}</strong><br>
                                        {{$book->author}}
                                    </a>
                                </td>
                                <td>{{$book->pivot->status}}</td>
                                <td>{{$book->pivot->type}}</td>
                                <td>{{$book->pivot->price ? '&euro;'.$book->pivot->price : ''}}</td>
                                <td>
                                    <a href="{{route('book.edit', $book->pivot->id)}}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('book.remove', $book->pivot->id)}}">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No books yet.</td>
                        </tr>
                    @endif
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection