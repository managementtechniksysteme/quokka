<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogbookIndexRequest;
use App\Http\Requests\LogbookStoreRequest;
use App\Http\Requests\LogbookUpdateRequest;
use App\Models\Logbook;

class LogbookController extends Controller
{
    public function index(LogbookIndexRequest $request)
    {
        //
    }

    public function store(LogbookStoreRequest $request)
    {
        //
    }

    public function update(LogbookUpdateRequest $request, Logbook $logbook)
    {
        //
    }

    public function destroy(Logbook $logbook)
    {
        //
    }
}
