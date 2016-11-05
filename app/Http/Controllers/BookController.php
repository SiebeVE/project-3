<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

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
		debug("Start checking if book" . $request->book_isbn . " exists.");
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
			"type"      => implode($request->kind, ","),
			"condition" => $request->condition,
		]);

		dump($request);
		dump(true);
		debug("");
	}

	public function getFind () {
		$user = Auth::user();
		$books = Book::with('owners')->get();
		// Get distance from books
		$availableBooks = [];
		$owner_ids = [];
		$owners = [];
		foreach ($books as $book) {
			debug("Check if book " . $book->title . " has an owner");
			if (count($book->owners)) {
				debug("'" . $book->title . "' has an owner!");
				$newBook = [
					"title"  => $book->title,
					"author" => $book->author,
					"type"   => [],
				];
				foreach ($book->owner as $owner) {
					if ( ! in_array($owner->id, $owner_ids)) {
						// Make a list of unique owners
						$owner_ids[] = $owner->id;
						$owners[] = $owner;
					}
					$types = explode(',', $owner->pivot->type);
					foreach ($types as $type) {
						if ( ! array_key_exists($type, $newBook["type"])) {
							$newBook["type"][ $type ] = [];
						}
						$newBook["type"][ $type ][] = [
							"owner_id" => $owner->id,
							"distance" => 0,
							"closest"  => false
						];
					}
				}

				$availableBooks[] = $newBook;
			}
		}

		$destinations = "";
		foreach ($owners as $owner) {
			$destinations .= $owner->getAddress() . "|";
		}
		$destinations = str_replace(' ', '+', rtrim($destinations, '|'));
		$client = new \GuzzleHttp\Client();
		$res = $client->request('GET', env("API_URL_DISTANCE"), [
			'query' => [
				'origins'        => $user->getAddress(),
				'destinations'   => $destinations,
				'departure_time' => time(),
				'traffic_model'  => 'best_guess',
				'mode'           => 'bicycling',
				'key'            => env("API_KEY_BOOK"),
			]
		]);

		$distanceResult = \GuzzleHttp\json_decode($res->getBody());


		// Make array with owner id and distance
		$distanceArray = [];
		foreach ($owner_ids as $key => $owner_id) {
			$distance = $distanceResult->rows[0]->elements[ $key ]->distance->value;

			$distanceArray[ $owner_id ]["distance"] = [
				"value" => $distance,
				"text"  => $distanceResult->rows[0]->elements[ $key ]->distance->text
			];

			$distanceArray[ $owner_id ]["duration"] = [
				"value" => $distanceResult->rows[0]->elements[ $key ]->duration->value,
				"text"  => $distanceResult->rows[0]->elements[ $key ]->duration->text
			];
		}

		uasort($distanceArray, "compare_by_int_key");

		//Relink the books with the owner and distance
		foreach ($availableBooks as $key_book => $book) {
			foreach ($book["type"] as $key_type => $type) {
				$closest = NULL;
				$closest_id = NULL;
				foreach ($type as $key_owner => $owner) {
					$distanceOwner = $distanceArray[ $owner["owner_id"] ];
					if ($closest == NULL) {
						$closest = $distanceOwner["duration"]["value"];
						$closest_id = $owner["owner_id"];
					}
					if ($distanceOwner["duration"]["value"] <= $closest) {
						$closest = $distanceOwner["duration"]["value"];
						$closest_id = $owner["owner_id"];
					}
					$availableBooks[ $key_book ]["type"][ $key_type ][ $key_owner ]["distance"] = $distanceOwner;
				}

				foreach ($type as $key_owner => $owner) {
					if ($owner["owner_id"] == $closest_id) {
						$availableBooks[ $key_book ]["type"][ $key_type ][ $key_owner ]["closest"] = true;
					}
				}
			}
		}

		return view('book.find', compact('availableBooks'));
	}

	private function getBookDetails ($bookId) {
		$client = new \GuzzleHttp\Client();
		$res = $client->request('GET', env("API_URL_BOOK") . "/" . $bookId, [
			'query' => ['key' => env("API_KEY_BOOK")]
		]);
		$bookResult = \GuzzleHttp\json_decode($res->getBody());

		return $bookResult;
	}

	/**
	 * Displays detailed information about a book
	 *
	 * @param Book $book
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view (Book $book) {
		$book = $book->with('owners')->get();

		return view('book.view', compact('book'));
	}
}
