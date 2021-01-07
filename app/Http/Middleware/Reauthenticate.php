<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Reauthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::pull('reauth.reauthenticated')) {
            Session::remove('reauth');

            return $next($request);
        } else {
            Session::flash('reauth.requested_url', $request->fullUrl());

            return redirect()->route('reauthenticate');
        }
    }
}
