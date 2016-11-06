@extends('layouts.app')

@section('header_left')
    <a href="/">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
        Home
    </a>
@endsection

@section('content')

    <div class="container">
        <nav class="product-filter">
            <!--<h1>The Library</h1>

            <div class="sort">
                <div class="collection-sort">
                    <label>Filter by:</label>
                    <select>
                        <option value="/">All Books</option>
                        <option value="/">Buy</option>
                        <option value="/">Free</option>
                        <option value="/">Borrow</option>
                    </select>
                </div>

                <div class="collection-sort">
                    <label>Sort by:</label>
                    <select>
                        <option value="/">Location</option>
                        <option value="/">Alphabetically, A-Z</option>
                        <option value="/">Alphabetically, Z-A</option>
                        <option value="/">Price, low to high</option>
                        <option value="/">Price, high to low</option>
                        <option value="/">State, new to old</option>
                        <option value="/">State, old to new</option>
                    </select>
                </div>
            </div>-->
        </nav>


        <!-- card list -->
        <div class="flex-card-list">
        @foreach($books as $book)
            <!-- card list item -->
                <a href="{{route('book.view', $book->id)}}" class="flex-card-listitem">
                    <!-- card module -->
                    <div class="flex-card">
                        <!-- image container -->
                        <div class="flex-card-image">
                            <img src="{!! $book->image !!}" alt="{{$book->title}}">
                        </div>
                        <!-- content container -->
                        <div class="flex-card-content">
                            <h3>{{$book->title}}</h3>
                            <span>
                                <i class="fa fa-bicycle" aria-hidden="true"></i>
                                {{$book->types[0][0]->distance->duration->text}}
                            </span>
                            <span class="pull-right">
                                <i class="fa fa-eur" aria-hidden="true"></i>
                                5,00
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{--<section class="products">--}}

        {{--@foreach($books as $book)--}}
        {{--<div class="product-card">--}}
        {{--<div class="product-image">--}}
        {{--<img src="{!! $book->image !!}" alt="{{$book->title}}">--}}
        {{--</div>--}}
        {{--<div class="product-info">--}}
        {{--<h3>{{$book->title}}</h3>--}}
        {{--<h4>{{$book->price}}</h4>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@endforeach--}}

        {{--</section>--}}
    </div>

@endsection
