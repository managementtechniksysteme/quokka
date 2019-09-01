<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Reauthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->pull('reauth.reauthenticated')) {
            return $next($request);
        } else {
            session()->flash('reauth.requested_url', $request->url());

            return redirect()->route('reauthenticate');
        }
    }
}
