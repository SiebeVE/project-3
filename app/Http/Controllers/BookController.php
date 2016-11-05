<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

use App\Http\Requests;

class BookController extends Controller
{
	public function getAdd () {
		return view('book.add');
	}

	public function getAddDetail ($bookId) {
		$bookDetails = $this->getBookDetails($bookId);

		return view('book.addDetail', ["book" => $bookDetails]);
	}

	public function postAddDetail (Request $request, $bookId) {
		$bookDetails = $this->getBookDetails($bookId);
		//dd($request);

		// Search if book already exists
		$book = Book::where('isbn', $request->book_isbn)->get();
		debug("Start checking if book".$request->book_isbn." exists.");
		if (count($book) > 0) {
			debug("The book exists!");
			$book = $book->first();
		}
		else {
			debug("The book doesn't exist, create a new one.");
			$book = Book::create([
				'title'       => $request->book_title,
				'isbn'        => $request->book_isbn,
				'image'       => $request->book_image,
				'description' => $request->book_description,
				'author'      => $request->book_authors,
				'pageCount'   => $request->book_pageCount ? $request->book_pageCount : NULL,
				'language'    => $request->book_language,
			]);
		}

		debug("Attach the book to the user");
		$user_book = $book->owner()->attach($request->user(), [
			"type" => implode($request->kind, ","),
			"condition" => $request->condition,
		]);

		dump($request);
		dump(true);
		debug("");
	}

	public function getFind () {
		$books = Book::get();
		//dd($books);

		return view('book.find', compact('books'));
	}

	private function getBookDetails ($bookId) {
		$client = new \GuzzleHttp\Client();
		$res = $client->request('GET', env("API_URL_BOOK") . "/" . $bookId, [
			'query' => ['key', env("API_KEY_BOOK")]
		]);
		$bookResult = \GuzzleHttp\json_decode($res->getBody());

		return $bookResult;
	}
}
