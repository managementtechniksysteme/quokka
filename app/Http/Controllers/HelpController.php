<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class HelpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('help.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (! View::exists("help.{$slug}")) {
            abort(404);
        }

        return view("help.{$slug}");
    }
}
