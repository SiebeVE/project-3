@extends('layouts.app')

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

        <section class="products">

            @foreach($books as $book)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{!! $book->image !!}" alt="{{$book->title}}">
                    </div>
                    <div class="product-info">
                        <h3>{{$book->title}}</h3>
                        <h4>{{$book->price}}</h4>
                    </div>
                </div>
            @endforeach

        </section>
    </div>

@endsection
