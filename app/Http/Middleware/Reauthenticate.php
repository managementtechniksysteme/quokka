<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Reauthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::get('reauthenticated')) {
            Session::remove('reauthenticated');
            Session::remove('requested_url');

            return $next($request);
        } else {
            Session::put('requested_url', $request->fullUrl());

            return redirect()->route('reauthenticate');
        }
    }
}
