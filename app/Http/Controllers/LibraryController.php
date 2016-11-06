<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Book;

class LibraryController extends Controller
{
    protected $books;
    protected $bookService;

    public function __construct(Book $books, BookService $bookService)
    {
        $this->bookService = $bookService;
        $this->books = $books;
    }

    public function index(Request $request) {
        $books = $this->books->with('ownersWithStatus0')->has('ownersWithStatus0')->get();

        $books = $this->bookService->getDistanceToBooksFromUser($books);

        return view('library', compact('books'));
    }
}
