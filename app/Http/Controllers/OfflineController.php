<?php

namespace App\Http\Controllers;

class OfflineController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('offline');
    }
}
