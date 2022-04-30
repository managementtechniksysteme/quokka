<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class ChangelogController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:help-view');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('help.changelog');
    }
}
