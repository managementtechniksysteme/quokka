<?php

namespace App\Http\Controllers;

class QrScanController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('qr-scan');
    }
}
