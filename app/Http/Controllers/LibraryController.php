<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Book;

class LibraryController extends Controller
{
    protected $books;

    public function __construct(Book $books)
    {
        $this->books = $books;
    }

    public function index(Request $request) {
        $books = $this->books->all();
        return view('library', compact('books'));
    }
}
