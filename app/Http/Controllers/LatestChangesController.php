<?php

namespace App\Http\Controllers;

use App\Support\GlobalSearch\GlobalSearch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LatestChangesController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tools-viewlatestchanges');
    }

    public function index()
    {
        $results = GlobalSearch::getLatestChanges(Auth::user()->settings->latest_changes_quantity)
            ->paginate(Auth::user()->settings->list_pagination_size);

        $changesToday = $results->filter(function ($result) {
            return $result->updated_at->isToday();
        });

        $changesYesterday = $results->filter(function ($result) {
            return $result->updated_at->isYesterday();
        });

        $now = Carbon::now();
        $startOfWeek = Carbon::now()->startOfWeek();

        $changesThisWeek = $results->filter(function ($result) use ($startOfWeek, $now) {
            return $result->updated_at->between($startOfWeek, $now);
        })->diffKeys($changesYesterday)->diffKeys($changesToday);

        $changesOlderThanThisWeek = $results
            ->diffKeys($changesThisWeek)
            ->diffKeys($changesYesterday)
            ->diffKeys($changesToday);

        return view('latest_changes.index')
            ->with(compact('results'))
            ->with(compact('changesToday'))
            ->with(compact('changesYesterday'))
            ->with(compact('changesThisWeek'))
            ->with(compact('changesOlderThanThisWeek'));
    }
}
