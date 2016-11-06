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

				@include('partials.errors')

				<form method="post">
					{{ csrf_field() }}
					@if( property_exists($book->volumeInfo, 'title') )
						<h1>{{ $book->volumeInfo->title }}</h1>
						@if(property_exists($book->volumeInfo, "authors"))
							<h2>by
								@foreach($book->volumeInfo->authors as $author)
									{{ $author }}{{ !$loop->last ? ($loop->remaining == 1 ? " and " : ", ") : "" }}
								@endforeach
							</h2>
						@endif
						
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
							@endif
							
							
							<div class="col-sm-8">
								@if( !property_exists($book->volumeInfo, 'title') )
									<div class="form-group">
										<label for="book_title">Title</label>
										<input class="form-control" type="text" name="book_title" id="book_title">
									</div>
								@endif
								@if(!property_exists($book->volumeInfo, "authors"))
									<div class="form-group">
										<label for="book_authors">Author(s)</label>
										<input class="form-control" type="text" name="book_authors" id="book_authors">
									</div>
								@endif
								
								@if(property_exists($book->volumeInfo, "description"))
									<div class="description">
										<p>
											{!! $book->volumeInfo->description !!}
										</p>
									</div>
								@else
									<div class="form-group">
										<label for="book_description">Description of book</label>
										<textarea class="form-control" name="book_description"
												  id="book_description"></textarea>
									</div>
								@endif
								
								@if(!property_exists($book->volumeInfo, "pageCount"))
									<div class="form-group">
										<label for="book_pageCount">Page count</label>
										<input class="form-control" type="number" name="book_pageCount"
											   id="book_pageCount">
									</div>
								@endif
								
								@if(!property_exists($book->volumeInfo, "language"))
									<div class="form-group">
										<label for="book_language">Language</label>
										<select id="book_language" name="book_language" class="form-control">
											@foreach($languages as $language)
												@if($language[0] != "" && $language[4] != "")
													<option value="{{$language[0]}}">{{$language[4]}}</option>
												@endif
											@endforeach
										</select>
									</div>
								@endif
								
								@if(!property_exists($book->volumeInfo, "industryIdentifiers"))
									<div class="form-group">
										<label for="book_isbn">ISBN</label>
										<input type="text" class="form-control" id="book_isbn" name="book_isbn">
									</div>
							@endif
							
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
											<label for="condition">Select condition</label>
											<select name="condition" id="condition"
													class="form-control condition-select">
												<option>Just as new</option>
												<option>Read</option>
												<option>Read with notes</option>
												<option>Damaged</option>
											</select>
										</div>
									</div>
									<div class="col-sm-6" id="priceField" style="display:none;">
										<div class="form-group">
											<label for="book_price">Set price</label>
											<div class="input-group">
												<span class="input-group-addon">&euro;</span>
												<input type="number" min="0" step="1" class="form-control"
													   id="book_price"
													   name="book_price">
											</div>
										</div>
									</div>
								</div>
								
								<input type="submit" class="btn btn-lg btn-primary" value="Add book to library!">
							</div>
						</div>
				</form>
			</div>
		
		
		</div>
	</div>
	</div>
	
	<script src="/js/libs/readmore.min.js"></script>
	<script>
		$(document).ready(function () {
			
			(function ( $ ) {
				
				$('.description').readmore();
				
				$('#priceField').hide();
				$('#toggleBuy').change(function () {
					// this will contain a reference to the checkbox
					if (this.checked) {
						$('#priceField').show();
					}
					else {
						$('#priceField').hide();
					}
				});
				
			})(jQuery);
			
		});
	
	</script>
@endsection