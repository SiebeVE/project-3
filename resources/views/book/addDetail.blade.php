@extends('layouts.app')

@section('header_left')
    <a href="{{route('book.add')}}">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
        Search book
    </a>
@endsection

@section('content')
    <div class="container">
        <div class="panel panel-book">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-plus"></i>
                    Add a book
                </h3>
            </div>
            <div class="panel-body">
                <h1>{{ $book->volumeInfo->title }}</h1>
                <h2>by
                    @if(property_exists($book->volumeInfo, "authors"))
                        @foreach($book->volumeInfo->authors as $author)
                            {{ $author }}{{ !$loop->last ? ($loop->remaining == 1 ? " and " : ", ") : "" }}
                        @endforeach
                    @endif
                </h2>

                <div class="row">
                    <div class="col-sm-4">
                        <img src="{{ property_exists($book->volumeInfo, "imageLinks") ? $book->volumeInfo->imageLinks->smallThumbnail : "/imgs/nocover1.png" }}"
                             class="bookcover" alt="{{$book->volumeInfo->title}}">
                        <div class="information">
                            <ul class="list-unstyled">
                                <li>
                                    <i class="fa fa-barcode" aria-hidden="true"></i>
                                    ISBN:
                                    <small>{{ $book->volumeInfo->industryIdentifiers[1]->identifier }}</small>
                                </li>
                                @if(property_exists($book->volumeInfo, "pageCount"))
                                    <li>
                                        <i class="fa fa-book" aria-hidden="true"></i>
                                        {{ $book->volumeInfo->pageCount}} pages
                                    </li>
                                @endif
                                @if( property_exists($book->volumeInfo, "language"))
                                    <li>
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                        {{getFullLanguageFromISO639($book->volumeInfo->language)}}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>


                    <div class="col-sm-8">
                        @if(property_exists($book->volumeInfo, "description"))
                            <div class="description">
                                <p>
                                    {!! $book->volumeInfo->description !!}
                                </p>
                            </div>
                        @else
                            <div class="well">No description available...</div>
                        @endif


                        <form method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="book_title" value="{{ $book->volumeInfo->title }}">
                            <input type="hidden" name="book_isbn"
                                   value="{{ $book->volumeInfo->industryIdentifiers[1]->identifier }}">
                            <input type="hidden" name="book_authors"
                                   value="{{ property_exists($book->volumeInfo, "authors") ? join(' and ', array_filter(array_merge(array(join(', ', array_slice($book->volumeInfo->authors, 0, -1))), array_slice($book->volumeInfo->authors, -1)), 'strlen')) : "" }}">
                            <input type="hidden" name="book_image"
                                   value="{{ property_exists($book->volumeInfo, "imageLinks") ? $book->volumeInfo->imageLinks->smallThumbnail : "" }}">
                            <input type="hidden" name="book_description"
                                   value="{{ property_exists($book->volumeInfo, "description") ? $book->volumeInfo->description : ""}}">
                            <input type="hidden" name="book_pageCount"
                                   value="{{ property_exists($book->volumeInfo, "pageCount") ? $book->volumeInfo->pageCount : ""}}">
                            <input type="hidden" name="book_language"
                                   value="{{ property_exists($book->volumeInfo, "language") ? $book->volumeInfo->language : ""}}">

                            <!-- Custom checkboxes -->
                            <div class="form-group">
                                <div class="toggle-button toggle-button--vesi">
                                    <input id="toggleFree" type="checkbox" name="kind[]" value="free">
                                    <label for="toggleFree" data-on-text="Free pick-up available"
                                           data-off-text="free pick-up"></label>
                                    <div class="toggle-button__icon"></div>
                                </div>

                                <div class="toggle-button toggle-button--vesi">
                                    <input id="toggleBorrow" type="checkbox" name="kind[]" value="borrow">
                                    <label for="toggleBorrow" data-on-text="available to borrow"
                                           data-off-text="borrowing"></label>
                                    <div class="toggle-button__icon"></div>
                                </div>

                                <div class="toggle-button toggle-button--vesi">
                                    <input id="toggleBuy" type="checkbox" name="kind[]" value="buy">
                                    <label for="toggleBuy" data-on-text="available to buy"
                                           data-off-text="buying"></label>
                                    <div class="toggle-button__icon"></div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Select condition</label>
                                        <select name="condition" class="form-control condition-select">
                                            <option>Just as new</option>
                                            <option>Read</option>
                                            <option>Read with notes</option>
                                            <option>Damaged</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6" id="priceField" style="display:none;">
                                    <div class="form-group">
                                        <label>Set price</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">&euro;</span>
                                            <input type="number" step="1" class="form-control" name="book_price">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-lg btn-primary" value="Add book to library!">
                        </form>
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

                $('#priceField').hide();
                $('#toggleBuy').change(function() {
                    // this will contain a reference to the checkbox
                    if (this.checked) {
                        $('#priceField').show();
                    } else {
                        $('#priceField').hide();
                    }
                });

            })(jQuery);

        });

    </script>
@endsection