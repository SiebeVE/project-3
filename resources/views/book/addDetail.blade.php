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
								<p>{{ property_exists($book->volumeInfo, "pageCount") ? $book->volumeInfo->pageCount : "Not defined"}} pages</p>
								<p>Language: {{ property_exists($book->volumeInfo, "language") ? $book->volumeInfo->language : "Not defined"}}</p>
							</div>
							<form>
								<label>
									<input type="checkbox" name="kin"> Free
								</label>
								<label>
									<input type="checkbox" name="kin"> Borrow
								</label>
								<label>
									<input type="checkbox" name="kin"> Buy
								</label>
								<label>
									Condition:
									<select>
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