<?php

namespace App\Http\Controllers;

use App\Models\Person;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $people = Person::whereNotNull('email')->order()->get();

        $currentTo = collect()->push(Person::find(1));

        $currentCC = collect()->push(Person::find(2));

        return view('home')
            ->with('people', $people->toJson())
            ->with('currentTo', $currentTo->toJson())
            ->with('currentCC', $currentCC->toJson())
            ->with('currentBCC', null);
    }
}
