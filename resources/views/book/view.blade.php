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

                        {{-- Status--}}
                        @include('partials.status')

                        <div class="panel panel-default">
                            <div class="panel-heading clickable" data-toggle="collapse" data-target="#owners">
                                <h3 class="panel-title">
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    {{$book->ownersWithStatus0->count()}} available
                                </h3>
                            </div>
                            <div class="collapse" id="owners">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>Distance</th>
                                            <th>Condition</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($book->ownersWithStatus0 as $owner)
                                            <tr>
                                                <td>{{$owner->firstname}}</td>
                                                <td><i class="fa fa-bicycle"></i> 4 minutes away</td>
                                                <td>{{$owner->pivot->condition}}</td>
                                                <td>
                                                    @foreach(explode(',',$owner->pivot->type) as $type)
                                                        <a href="{{route('book.buyorborrow', ["type"=>$type, "bookUser"=>$owner->pivot->id])}}" class="btn btn-sm btn-primary">
                                                            {{$type == 'free' ? 'pick-up for free' : $type}}
                                                            {{ $type == 'buy' ? 'for '. $owner->pivot->price : ''}}
                                                        </a>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="description">
                            <p>
                                {!!nl2br($book->description)!!}
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