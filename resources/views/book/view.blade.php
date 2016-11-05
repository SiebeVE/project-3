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
                        <div class="col-md-12 information">
                            <div class="row">
                                <div class="col-xs-1"><i class="fa fa-barcode" aria-hidden="true"></i></div>
                                <div class="col-xs-9">
                                    ISBN: <br>
                                    <small>{{$book->isbn}}</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-1"><i class="fa fa-book" aria-hidden="true"></i></div>
                                <div class="col-xs-9">{{$book->pageCount}} pages</div>
                            </div>
                            <div class="row">
                                <div class="col-xs-1"><i class="fa fa-globe" aria-hidden="true"></i> </div>
                                <div class="col-xs-9">{{$book->fullLanguage}}</div>
                            </div>
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