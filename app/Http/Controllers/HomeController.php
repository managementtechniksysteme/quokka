<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(Auth::user()->employee->isCurrentlyOnHoliday() && !$request->has('skip-holiday')) {
            return view('holiday');
        }

        return view('home');
    }
}
