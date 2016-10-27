<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class BookController extends Controller {
	public function getAdd() {
		return view( 'book.add' );
	}

	public function getAddDetail( $bookId ) {
		$client = new \GuzzleHttp\Client();
		$res    = $client->request( 'GET', env( "API_URL_BOOK" )."/".$bookId, [
			'query' => [ 'key', env( "API_KEY_BOOK" ) ]
		] );
		$bookResult = \GuzzleHttp\json_decode($res->getBody());

		//dd($bookResult);

		return view( 'book.addDetail', ["book" => $bookResult] );
	}
}
