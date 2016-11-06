@extends('layouts.app')

@section('header_left')
	<a href="/">
		<i class="fa fa-chevron-left" aria-hidden="true"></i>
		Home
	</a>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Find a book</div>
					
					<div class="panel-body">
						<div id="book-list">
							<form>
								<div class="input-field">
									<label for="search">Search:</label>
									<input id="search" class="fuzzy-search" type="search"/>
									<button type="button" class="search">Search</button>
								</div>
								<div class="input-field">
									<span class="sort" data-sort="title">Sort by title</span>
									<span class="sort" data-sort="author">Sort by author</span>
								</div>
							</form>
							<ul class="list" id="search-results">
								@foreach($availableBooks as $book)
									<li>
										<h4 class="title">{{ $book['title'] }}</h4>
										<p class="author">{{ $book['author'] }}</p>
										@if($book['types'])
											@foreach($book['types'] as $typename => $type)
												<span class="type">{{ $typename }}</span>
												@foreach($type as $owned)
													@if($owned["closest"])
														<span class="distance">({{ $owned["distance"]["duration"]["text"] }})</span>
													@endif
												@endforeach
											@endforeach
										@endif
									</li>
								@endforeach
							</ul>
							<ul class="pagination"></ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="/js/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.3.0/list.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/list.pagination.js/0.1.1/list.pagination.min.js"></script>
	<script src="/js/list.fuzzysearch.min.js"></script>
	<script>
		var options = {
			valueNames: [ 'title', 'author', 'type' ],
			page: 10,
			plugins: [
				ListFuzzySearch(),
				ListPagination({})
			]
		};
		
		var listObj = new List('book-list', options);
	</script>
@endsection