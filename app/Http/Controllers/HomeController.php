<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
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
