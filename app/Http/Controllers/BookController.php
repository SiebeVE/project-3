<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookTransaction;
use App\BookUser;
use App\Services\BookService;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    protected $bookService;
    protected $books;

    public function __construct(BookService $bookService, Book $books)
    {
        $this->books = $books;
        $this->bookService = $bookService;
    }

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
	    $availableBooks = $this->bookService->getDistanceToBooksFromUser($this->books->get());
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

	public function getBuyOrBorrow ($type, BookUser $bookUser) {
		if ($type == "buy" || $type == "borrow") {
			$typeArray = explode(',', $bookUser->type);

			if ( ! in_array($type, $typeArray) || $bookUser->status != 0) {
				abort(400, "You cannot " . $type . " this book.");
			}
			else if ($bookUser->user->id == Auth::user()->id) {
				abort(400, "You cannot " . $type . " your own book.");
			}

			$toUser = Auth::user();
			$fromUser = $bookUser->user;

			$transaction = BookTransaction::create([
				"book_id" => $bookUser->id,
				"from_id" => $fromUser->id,
				"to_id"   => $toUser->id,
				"type"    => $type,
			]);

			switch ($type) {
				case "buy":
					$bookUser->status = 1;
					break;
				case "borrow":
					$bookUser->status = 3;
					break;
			}
			$bookUser->save();
		}
		else {
			abort(401, "That action is not allowed.");
		}

		return $bookUser;
	}

	public function getConfirmRecieved ($type, BookTransaction $transaction) {
		if ($type == "borrow" || $type == "buy") {
			// Only allow the user
			$toUser = Auth::user();

			if ($toUser->id != $transaction->to_id) {
				abort(403, "This user is not allowed to finish this transaction.");
			}

			$book = $transaction->book;
			if (!($book->status == 1 || $book->status == 3)) {
				abort(401, "The book is no longer traveling.");
			}

			switch ($type) {
				case "buy":
					$newBook = $book->replicate();
					$newBook->push();

					$newBook->status = 0;
					$newBook->user_id = $toUser->id;
					$newBook->price = 0;
					$newBook->type = "borrow";
					$newBook->save();

					$book->status = 2;
					break;
				case "borrow":
					$book->status = 4;
					break;
			}
			$book->save();
		}
		else {
			abort(401, "That action is not allowed.");
		}

		return "done";
	}

	public function getConfirmGiveBack (BookTransaction $transaction) {
		// Only user of the book can confirm it
		if(Auth::user()->id != $transaction->from_id)
		{
			abort(403, "This user isn't allowed to do this action.");
		}

		if($transaction->book->status != 4)
		{
			abort(401, "This book is not with the other party.");
		}

		$transaction->book->status = 0;
		$transaction->book->save();

		return "done";
	}

	/**
	 * Displays detailed information about a book
	 *
	 * @param Book $book
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view (Book $book) {
	    $book = $book->with('owners')->findOrFail($book->id);

		return view('book.view', compact('book'));
	}
}