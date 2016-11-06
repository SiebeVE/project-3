<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookTransaction;
use App\BookUser;
use App\ISO639;
use App\Notifications\BookGiveBack;
use App\Notifications\BookGiveBackSend;
use App\Notifications\BookReceived;
use App\Notifications\BookReceivedSend;
use App\Notifications\BorrowRequest;
use App\Notifications\BorrowRequestSend;
use App\Services\BookService;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use stdClass;

class BookController extends Controller
{
	protected $bookService;
	protected $books;

	public function __construct (BookService $bookService, Book $books) {
		$this->books = $books;
		$this->bookService = $bookService;
	}


	// Shows the user's books
	public function index () {
		$books = Auth::user()->books;

		return view('book.index', compact('books'));
	}

	public function getAdd () {
		return view('book.add');
	}

	public function getAddDetail (Request $request, $bookId) {
		$bookDetails = $this->getBookDetails($bookId);

		$iso = new ISO639();

		if (property_exists($bookDetails->volumeInfo, "language")) {
			$bookDetails->volumeInfo->fullLanguage = $iso->languageByCode1($bookDetails->volumeInfo->language);
		}

		$languages = $iso->getLanguages();

		$request->session()->put('book', $bookDetails);

		return view('book.addDetail', ["book" => $bookDetails, "languages" => $languages]);
	}

	public function getAddNew (Request $request) {
		$iso = new ISO639();
		$languages = $iso->getLanguages();

		$book = new stdClass;
		$book->volumeInfo = new stdClass;

		$request->session()->forget('book');

		return view('book.addDetail', ["book" => $book, "languages" => $languages]);
	}

	public function postAddDetail (Request $request, $bookId) {
		$sessionBook = new stdClass;
		$sessionBook->volumeInfo = new stdClass;

		if ($request->session()->exists('book')) {
			debug("exists session");
			$sessionBook = $request->session()->get('book');
		}

		$bookISBN = property_exists($sessionBook->volumeInfo, "industryIdentifiers") ? $sessionBook->volumeInfo->industryIdentifiers[1]->identifier : $request->book_isbn;

		// Search if book already exists
		$book = Book::where('isbn', $bookISBN)->get();
		debug("Start checking if book" . $bookISBN . " exists.");
		if (count($book) > 0) {
			debug("The book exists!");
			$book = $book->first();
		}
		else {
			debug("The book doesn't exist, create a new one.");
			$book = Book::create([
				'title'       => property_exists($sessionBook->volumeInfo, "title") ? $sessionBook->volumeInfo->title : $request->book_title,
				'isbn'        => $bookISBN,
				'image'       => property_exists($sessionBook->volumeInfo, "imageLinks") ? $sessionBook->volumeInfo->imageLinks->smallThumbnail : $request->book_image,
				'description' => property_exists($sessionBook->volumeInfo, "description") ? $sessionBook->volumeInfo->description : htmlentities($request->book_description, ENT_QUOTES),
				'author'      => property_exists($sessionBook->volumeInfo, "authors") ? $this->makeAuthorsString($book->volumeInfo->authors) : $request->book_authors,
				'pageCount'   => property_exists($sessionBook->volumeInfo, "pageCount") ? $sessionBook->volumeInfo->pageCount : $request->book_pageCount,
				'language'    => property_exists($sessionBook->volumeInfo, "language") ? $sessionBook->volumeInfo->language : $request->book_language,
			]);
		}

		debug("Attach the book to the user");

		$user_book = $book->owner()->attach($request->user(), [
			"type"      => implode($request->kind, ","),
			"condition" => $request->condition,
			"price"     => $request->book_price == "" ? NULL : intval($request->book_price),
		]);

//		dump($request);
//		dump(true);
		debug("");

		return view('book.added');
	}

	public function getFind () {
		// Get books with owners that are willing to sell
		$availableBooks = $this->books->with('ownersWithStatus0')->has('ownersWithStatus0')->get();

		// Add the distances to the types
		if (Auth::user()) {
			$availableBooks = $this->bookService->getDistanceToBooksFromUser($availableBooks);
		}

		return view('book.find', compact('availableBooks'));
	}


	private function makeAuthorsString ($authors) {
		return join(' and ', array_filter(array_merge(array(join(', ', array_slice($authors, 0, - 1))), array_slice($authors, - 1)), 'strlen'));
	}

	private function getBookDetails ($bookId) {
		$client = new \GuzzleHttp\Client();
		$res = $client->request('GET', env("API_URL_BOOK") . "/" . $bookId, [
			'query' => ['key' => env("API_KEY_BOOK")]
		]);

		$bookResult = \GuzzleHttp\json_decode($res->getBody());

		return $bookResult;
	}

	public function getTransaction (BookTransaction $transaction) {
		return $transaction;
	}

	public function getBuyOrBorrow ($type, BookUser $bookUser) {
		if ($type == 'free') {
			$type = 'buy';
		}

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

			$bookUser->user->notify(new BorrowRequest(Auth::user(), $bookUser, $type));

			$toUser->notify(new BorrowRequestSend($type, $transaction, $bookUser));
		}
		else {
			abort(401, "That action is not allowed.");
		}

		return redirect()->back()->with('status', "Request to {$type} this book sent!");
	}

	public function getConfirmGiveBack (BookTransaction $transaction) {
		// Only user of the book can confirm it

		$fromUser = Auth::user();
		if ($fromUser->id != $transaction->from_id) {
			abort(403, "This user isn't allowed to do this action.");
		}

		if ($transaction->book->status != 4) {
			abort(401, "This book is not with the other party.");
		}

		$toUser = $transaction->to;

		$transaction->book->status = 0;
		$transaction->book->save();

		$fromUser->notify(new BookGiveBack($toUser, $transaction->book));
		$toUser->notify(new BookGiveBackSend($fromUser, $transaction->book));

		return "done";
	}

	public function getConfirmRecieved ($type, BookTransaction $transaction) {
		if ($type == "borrow" || $type == "buy") {
			// Only allow the user
			$toUser = Auth::user();

			if ($toUser->id != $transaction->to_id) {
				abort(403, "This user is not allowed to finish this transaction.");
			}

			$fromUser = $transaction->from;

			$book = $transaction->book;
			if ( ! ($book->status == 1 || $book->status == 3)) {
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

			$fromUser->notify(new BookReceived($type, $toUser, $book, $transaction));
			$toUser->notify(new BookReceivedSend($type, $fromUser, $book));
		}
		else {
			abort(401, "That action is not allowed.");
		}

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

		$book = $book->with([
			'ownersWithStatus0' => function ($q) {
				$q->where('users.id', '<>', Auth::user()->id);
			}
		])->findOrFail($book->id);
		debug($book);

		return view('book.view', compact('book'));
	}
}