<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
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
        $files = File::allFiles(resource_path('views/help'));

        $names = array_map(function($element) {
            return $element->getBasename(".blade.php");
        }, $files);

        $index = array_search('index', $names);

        if($index !== false){
            unset($names[$index]);
        }

        return view('help.index')->with(compact('names'));
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
