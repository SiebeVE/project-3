<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;

class UserController extends Controller
{
    public function view() {
        $user = Auth::user();
        return view('user.view', compact('user'));
    }

    public function edit() {
        $user = Auth::user();
        return view('user.form', compact('user'));
    }

    /**
     * Update the user profile
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UserStoreRequest $request)
    {
        $user = Auth::user();

        $user->fill(
            $request->only(
                'name',
                'email',
                'street',
                'number',
                'city',
                'postal',
                'country'
            )
        )->save();
        return redirect()->route('user.view')->with('status', 'Your information has been saved!');
    }
}
