<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GlobalSecurity
{
    // check the user is delegate
    public function handle($request, Closure $next)
    {
        if (Auth::user()->auth_token_allow == 1 and Auth::user()->auth_token != '') {
            Auth::logout();
            Session::flush();
            Session::flash('error', "2nd Step verification not match properly. Please login again.");

            /*
             *
             * MOBILE LOGOUT CHECK
             *
             */
            return redirect('login');
        }
        // if he/she is delegate parson
        if (Auth::user()->delegate_to_user_id != 0) {
            return redirect('users/delegate');
        }

        return $next($request);
    }
}
