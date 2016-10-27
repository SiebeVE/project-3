@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div>{{ dump($book) }}</div>
				<div class="panel panel-default">
					<div class="panel-heading">Add a book</div>
					<div class="panel-body">
						<section>
							<h2>{{ $book->volumeInfo->title }}</h2>
							<p>by
								@if(property_exists($book->volumeInfo, "authors"))
									@foreach($book->volumeInfo->authors as $author)
										{{ $author }}{{ !$loop->last ? ($loop->remaining == 1 ? " and " : ", ") : "" }}
									@endforeach
								@endif
							</p>
							<div>
								<img src="{{ property_exists($book->volumeInfo, "imageLinks") ? $book->volumeInfo->imageLinks->smallThumbnail : "https://www.hachettebookgroup.com/_b2c/static/site_theme/img/missingbook.png" }}"
									 align="left">
								{!! property_exists($book->volumeInfo, "description") ? $book->volumeInfo->description : "Not defined"!!}
								<p>{{ property_exists($book->volumeInfo, "pageCount") ? $book->volumeInfo->pageCount : "Not defined"}}
									pages</p>
								<p>
									Language: {{ property_exists($book->volumeInfo, "language") ? $book->volumeInfo->language : "Not defined"}}</p>
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
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection