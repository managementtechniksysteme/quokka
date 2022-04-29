<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogbookIndexRequest;
use App\Http\Requests\LogbookStoreRequest;
use App\Http\Requests\LogbookUpdateRequest;
use App\Models\ApplicationSettings;
use App\Models\Logbook;
use App\Models\Person;
use App\Models\Project;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogbookController extends Controller
{
    public function index(LogbookIndexRequest $request)
    {
        if($request->ajax()) {
            $currentLogbook = Logbook::filterSearch($request->validated())->order()->get();

            return response()->json($currentLogbook, Response::HTTP_OK);
        }

        $origins = Logbook::distinct('origin')->select('origin AS place');
        $places = Logbook::distinct('destination')
            ->select('destination AS place')
            ->union($origins)
            ->orderBy('place')
            ->pluck('place');

        $vehicles = Vehicle::order()->get();
        $projects = Project::order()->get();
        $employees = Person::has('employee')->order()->get();
        $currentEmployee = Auth::user()->employee->person;
        $expandErrors = Auth::user()->settings->accounting_expand_errors;
        $filterDefaultDays = Auth::user()->settings->accounting_filter_default_days;
        $pageSize = Auth::user()->settings->list_pagination_size;

        return view('logbook.index')
            ->with('currentLogbook', null)
            ->with('places', $places->toJson())
            ->with('vehicles', $vehicles->toJson())
            ->with('projects', $projects->toJson())
            ->with('employees', $employees->toJson())
            ->with('currentEmployee', $currentEmployee->toJson())
            ->with('expandErrors', json_encode($expandErrors))
            ->with('filterDefaultDays', json_encode($filterDefaultDays))
            ->with('pageSize', json_encode($pageSize));
    }

    public function store(LogbookStoreRequest $request)
    {
        $logbook = Logbook::make($request->validated());
        $logbook->employee()->associate(Auth::user()->employee);
        $logbook->save();

        return response()->json($logbook, Response::HTTP_CREATED);
    }

    public function update(LogbookUpdateRequest $request, Logbook $logbook)
    {
        $logbook->update($request->validated());

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(Logbook $logbook)
    {
        $logbook->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
