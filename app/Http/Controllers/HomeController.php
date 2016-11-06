<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Book;

class HomeController extends Controller
{
    protected $books;
    protected $bookService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Book $books, BookService $bookService)
    {
        $this->bookService = $bookService;
        $this->books = $books;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $randomBooks = $this->books->with('ownersWithStatus0')->has('ownersWithStatus0')->where('image', '<>', '')->inRandomOrder()->limit(4)->get();

        return view('home', compact('randomBooks'));
    }

	public function getNotifications () {
		$user = Auth::user();

		$unread = $user->unreadNotifications;
		$read = $user->readNotifications;

		$user->unreadNotifications->markAsRead();

		return view('notifications', [
			"notifications" => [
				"read" => $read,
				"unread" => $unread
				],
		]);
    }
}
