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
                    <div class="col-sm-4">
                        <img src="{{$book->image}}" class="bookcover" alt="{{$book->title}}">
                        <div class="information">
                            <ul class="list-unstyled">
                                <li>
                                    <i class="fa fa-barcode" aria-hidden="true"></i>
                                    ISBN: <small>{{$book->isbn}}</small>
                                </li>
                                <li>
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                    {{$book->pageCount}} pages
                                </li>
                                <li>
                                    <i class="fa fa-globe" aria-hidden="true"></i>
                                    {{$book->fullLanguage}}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    7 available
                                </h3>
                            </div>
                            <table class="table">

                            </table>
                        </div>

                        <div class="description">
                            <p>
                                {!!$book->description!!}
                            </p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script src="/js/libs/readmore.min.js"></script>
    <script>
        $(document).ready(function(){

            (function($) {

                $('.description').readmore();

            })(jQuery);

        });

    </script>
@endsection