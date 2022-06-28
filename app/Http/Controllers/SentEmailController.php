<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class SentEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tools-viewsentemails');
    }

    public function index(Request $request)
    {
        $activities = Activity::forEvent('emailSent')
            ->when($request->has('search'), function ($query) use ($request) {
                return $query->where('properties', 'like', '%'.$request->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(Auth::user()->settings->list_pagination_size);

        return view('sent_email.index')
            ->with(compact('activities'));
    }
}
