@extends('layouts.app')

@section('header_left')
		<a href="#">Booksharing</a>
@endsection

@section('content')
	<div class="flex-container">
		<a href="{{route('book.add')}}" class="btn btn-lg btn-primary">Add book</a>
		<a href="{{route('library')}}" class="btn btn-lg btn-primary">View library</a>
	</div>
@endsection
