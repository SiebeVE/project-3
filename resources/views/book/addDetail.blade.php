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
						<img src="{{ property_exists($book->volumeInfo, "imageLinks") ? $book->volumeInfo->imageLinks->smallThumbnail : "/imgs/nocover1.png" }}" class="bookcover" alt="{{$book->volumeInfo->title}}">
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
					</div>

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
						<label>
							<input type="checkbox" name="kind[]" value="free"> Free
						</label>
						<label>
							<input type="checkbox" name="kind[]" value="borrow"> Borrow
						</label>
						<label>
							<input type="checkbox" name="kind[]" value="buy"> Buy
						</label>
						<label>
							Condition:
							<select name="condition">
								<option>Just as new</option>
								<option>Read</option>
								<option>Read with notes</option>
								<option>Damaged</option>
							</select>
						</label>
						<input type="submit" class="btn btn-success">
					</form>
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