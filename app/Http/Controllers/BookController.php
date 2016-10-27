<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

use App\Http\Requests;

class BookController extends Controller {
	public function getAdd() {
		return view( 'book.add' );
	}

	public function getAddDetail( $bookId ) {
		$bookDetails = $this->getBookDetails( $bookId );

		return view( 'book.addDetail', [ "book" => $bookDetails ] );
	}

	public function postAddDetail( Request $request, $bookId ) {
		$bookDetails = $this->getBookDetails( $bookId );
		//dd($request);

		$book = $request->user()->book()->create( [
			'title'       => $request->book_title,
			'isbn'        => $request->book_isbn,
			'image'       => $request->book_image,
			'description' => $request->book_description,
			'author'      => $request->book_authors,
			'pageCount'   => $request->book_pageCount,
			'language'    => $request->book_language,
			'type'        => implode( $request->kind, "," ),
			'condition'   => $request->condition,
		] );
		dd( $request );
	}

	private function getBookDetails( $bookId ) {
		$client     = new \GuzzleHttp\Client();
		$res        = $client->request( 'GET', env( "API_URL_BOOK" ) . "/" . $bookId, [
			'query' => [ 'key', env( "API_KEY_BOOK" ) ]
		] );
		$bookResult = \GuzzleHttp\json_decode( $res->getBody() );

		return $bookResult;
	}
}
