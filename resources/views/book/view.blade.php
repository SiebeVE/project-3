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
                <h2>by {{$book->author ?: 'unknown author'}}</h2>

                <div class="row">
                    <div class="col-sm-4">
                        <img src="{{$book->image}}" class="bookcover" alt="{{$book->title}}">
                        <div class="information">
                            <ul class="list-unstyled">
                                <li>
                                    <i class="fa fa-barcode" aria-hidden="true"></i>
                                    ISBN:
                                    <small>{{$book->isbn}}</small>
                                </li>
                                @if($book->pageCount)
                                    <li>
                                        <i class="fa fa-book" aria-hidden="true"></i>
                                        {{$book->pageCount}} pages
                                    </li>
                                @endif
                                @if($book->language)
                                    <li>
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                        {{$book->fullLanguage}}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>


                    <div class="col-sm-8">
                        <div class="panel panel-default">
                            <div class="panel-heading clickable" data-toggle="collapse" data-target="#owners">
                                <h3 class="panel-title">
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    {{$book->owners->count()}} available
                                </h3>
                            </div>
                            <div class="collapse" id="owners">
                                <table class="table">
                                    @foreach($book->owners as $owner)
                                        <tr>
                                            <td>{{$owner->pivot->condition}}</td>
                                            <td><i class="fa fa-bicycle" aria-hidden="true"></i> x minutes</td>
                                            <td>&euro; 5,00</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="description">
                            <p>
                                {!!$book->description!!}
                            </p>
                        </div>

                        @if(! $book->description)
                            <div class="well">No description available...</div>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script src="/js/libs/readmore.min.js"></script>
    <script>
        $(document).ready(function () {

            (function ($) {

                $('.description').readmore();

            })(jQuery);

        });

    </script>
@endsection