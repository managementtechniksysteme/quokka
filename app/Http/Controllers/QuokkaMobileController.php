<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class QuokkaMobileController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:help-view');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('quokka-mobile');
    }
}
