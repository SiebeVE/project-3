<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Don't do anything if the user is complete, not logged in or currently updating the profile
        if( ! Auth::user() || Auth::user()->isComplete() || $request->route()->getName() == 'user.edit' ||  $request->route()->getName() == 'user.update')  {
//            dump($request->route()->getName() );
            return $next($request);
        }
        // Redirect the user to complete profile information
        return redirect()->route('user.edit')->with('status', 'Please enter some information about yourself first!');
    }
}