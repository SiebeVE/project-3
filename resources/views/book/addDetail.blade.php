@extends('layouts.app')

@section('header_left')
	<a href="/book/add">
		<i class="fa fa-chevron-left" aria-hidden="true"></i>
		Add Book
	</a>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div>{{ dump($book) }}</div>
				<div class="panel panel-default">
					<div class="panel-heading">Add a book</div>
					<div class="panel-body">
						<section>
							<form method="post">
								<h2>{{ $book->volumeInfo->title }}</h2>
								{{--<input type="hidden" name="book_title" value="{{ $book->volumeInfo->title }}">--}}
								
								@if(property_exists($book->volumeInfo, "authors"))
									<p>by
										@foreach($book->volumeInfo->authors as $author)
											{{ $author }}{{ !$loop->last ? ($loop->remaining == 1 ? " and " : ", ") : "" }}
										@endforeach
										{{--<input type="hidden" name="book_authors"--}}
											   {{--value="{{ property_exists($book->volumeInfo, "authors") ? join(' and ', array_filter(array_merge(array(join(', ', array_slice($book->volumeInfo->authors, 0, -1))), array_slice($book->volumeInfo->authors, -1)), 'strlen')) : "" }}">--}}
									</p>
								@else
									<p>
										<label>
											Author(s)
											<input type="text" name="book_authors">
										</label>
									</p>
								@endif
								<div>
									<img src="{{ property_exists($book->volumeInfo, "imageLinks") ? $book->volumeInfo->imageLinks->smallThumbnail : "https://www.hachettebookgroup.com/_b2c/static/site_theme/img/missingbook.png" }}"
										 align="left">
									{{--<input type="hidden" name="book_image"--}}
										   {{--value="{{ property_exists($book->volumeInfo, "imageLinks") ? $book->volumeInfo->imageLinks->smallThumbnail : "" }}">--}}
									@if( property_exists($book->volumeInfo, "description"))
										{!! $book->volumeInfo->description !!}
										{{--<input type="hidden" name="book_description"--}}
											   {{--value="{{ property_exists($book->volumeInfo, "description") ? $book->volumeInfo->description : ""}}">--}}
									@else
										<label>
											Description
											<textarea name="book_description"></textarea>
										</label>
									@endif
									
									@if(property_exists($book->volumeInfo, "pageCount"))
										<p>{{ $book->volumeInfo->pageCount}} pages</p>
										{{--<input type="hidden" name="book_pageCount"--}}
{{--											   value="{{ property_exists($book->volumeInfo, "pageCount") ? $book->volumeInfo->pageCount : ""}}">--}}
									@else
										<p>
											<label>
												Number of pages
												<input type="number" name="book_pageCount">
											</label>
										</p>
									@endif
									
									@if(property_exists($book->volumeInfo, "language"))
										<p>
											Language: {{ $book->volumeInfo->fullLanguage }}</p>
										{{--<input type="hidden" name="book_language"--}}
{{--											   value="{{ property_exists($book->volumeInfo, "language") ? $book->volumeInfo->language : ""}}">--}}
									@else
										<p>
											<label>
												Language <input type="text" name="book_language">
											</label>
										</p>
									@endif
								</div>
								{{ csrf_field() }}
								{{--<input type="hidden" name="book_isbn"--}}
{{--									   value="{{ $book->volumeInfo->industryIdentifiers[1]->identifier }}">--}}
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
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection