<?php

namespace App\Http\Controllers;

class QrScanController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tools-scanqr');
    }

    public function index()
    {
        return view('qr-scan');
    }
}
