<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Support\GlobalSearch\GlobalSearch;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:search');
    }

    public function index(SearchRequest $request)
    {
        $validatedData = $request->validated();

        $results = GlobalSearch::searchFuzzy($validatedData['query'])
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('search.index')
            ->with('query', $validatedData['query'])
            ->with(compact('results'));
    }
}
