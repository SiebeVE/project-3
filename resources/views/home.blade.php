@extends('layouts.app')

@section('header_left')
		<a href="#">Booksharing</a>
@endsection

@section('content')
	<div class="flex-container flex-center">
		<div class="text-center">
			<h1 class="call-to-action">Have you read...</h1>

			@if($randomBooks)
				<!-- card list -->
				<div class="flex-card-list">
				@foreach($randomBooks as $book)
					<!-- card list item -->
						<a href="{{route('book.view', $book->id)}}" class="flex-card-listitem">
							<!-- card module -->
							<div class="flex-card">
								<!-- image container -->
								<div class="flex-card-image">
									<img src="{!! $book->image !!}" alt="{{$book->title}}">
								</div>
							</div>
						</a>
					@endforeach
				</div>
			@endif

			<div>
				<a href="{{route('book.add')}}" class="btn btn-lg btn-primary">Add book</a>
				<div class="clearfix visible-xs-block"></div>
				<a href="{{route('library')}}" class="btn btn-lg btn-primary">View library</a>
			</div>
		</div>
	</div>
@endsection
