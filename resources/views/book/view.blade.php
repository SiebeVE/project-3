@extends('layouts.app')

@section('header_left')
    <a href="{{route('library')}}">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
        Library
    </a>
@endsection

@section('content')
    <div class="container">
        <div class="panel panel-book">
            <div class="panel-body">
                <h1>{{$book->title}}</h1>
                <h2>by {{$book->author}}</h2>

                <div class="row">
                    <div class="col-xs-6">
                        <img src="{{$book->image}}" class="bookcover" alt="{{$book->title}}">
                    </div>
                    <div class="col-xs-6">
                        <div class="information">
                            <ul class="list-unstyled">
                                <li><i class="fa fa-book" aria-hidden="true"></i> {{$book->pageCount}} pages</li>
                                <li><i class="fa fa-barcode" aria-hidden="true"></i>ISBN: {{$book->isbn}}</li>
                                <li><i class="fa fa-globe" aria-hidden="true"></i> {{$book->fullLanguage}}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <p>
                    {!!$book->description!!}
                </p>
            </div>
        </div>
    </div>
@endsection