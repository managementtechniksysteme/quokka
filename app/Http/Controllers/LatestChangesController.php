<?php

namespace App\Http\Controllers;

use App\Support\GlobalSearch\GlobalSearch;
use Illuminate\Support\Facades\Auth;

class LatestChangesController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tools-scanqr');
    }

    public function index()
    {
        $results = GlobalSearch::getLatestChanges(50)
            ->paginate(Auth::user()->settings->list_pagination_size);

        return view('latest_changes.index')
            ->with(compact('results'));
    }
}
