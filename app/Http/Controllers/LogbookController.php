<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogbookDownloadRequest;
use App\Http\Requests\LogbookIndexRequest;
use App\Http\Requests\LogbookStoreRequest;
use App\Http\Requests\LogbookUpdateRequest;
use App\Models\ApplicationSettings;
use App\Models\Logbook;
use App\Models\Person;
use App\Models\Project;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use ZsgsDesign\PDFConverter\Latex;

class LogbookController extends Controller
{
    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'showEmail' => 'email',
            'email' => 'email',
        ]);
    }

    public function resourceMethodsWithoutModels()
    {
        return array_merge(parent::resourceMethodsWithoutModels(), ['download']);
    }

    public function __construct()
    {
        $this->authorizeResource(Logbook::class, 'logbook');
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $currentLogbook = Logbook::filterPermissions()
                ->filterSearch($request->validate((new LogbookIndexRequest($request->query()))->rules()))
                ->order()
                ->get();

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

        $logbookPermissions = Auth::user()
            ->permissions()
            ->where('name', 'LIKE', 'logbook%')
            ->select('name')
            ->pluck('name');

        return view('logbook.index')
            ->with('currentLogbook', null)
            ->with('places', $places->toJson())
            ->with('vehicles', $vehicles->toJson())
            ->with('projects', $projects->toJson())
            ->with('employees', $employees->toJson())
            ->with('permissions', $logbookPermissions->toJson())
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

    public function download(LogbookDownloadRequest $request)
    {
        $validatedData = $request->validated();

        $employee = isset($validatedData['only_own']) ? Auth::user()->employee->load('person') : null;
        $project = isset($validatedData['project_id']) ? Project::find($validatedData['project_id']) : null;
        $vehicle = isset($validatedData['vehicle_id']) ? Vehicle::find($validatedData['vehicle_id']) : null;

        $start = isset($validatedData['start']) ? Carbon::parse($validatedData['start']) : null;
        $end = isset($validatedData['end']) ? Carbon::parse($validatedData['end']) : null;

        $vehicleString = optional($vehicle)->registration_identifier ?? '';

        $startFormatted = optional($start)->format('Ymd') ?? '';
        $endFormatted = optional($end)->format('Ymd') ?? '';
        $rangeString = $startFormatted.($start&&$end ? '-' : '').$endFormatted;

        $fileName = 'FB' . ($vehicleString !== '' ? ' ' . $vehicleString : '') . ($rangeString !== '' ? ' ' . $rangeString : '') . '.pdf';

        $report = Logbook::filterPermissions()
            ->filterSearch($validatedData)
            ->order()
            ->with('employee.user')
            ->with('project')
            ->with('vehicle')
            ->get();

        $people = Person::whereIn('id', $report->unique('employee_id')->pluck('employee_id'))
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->with('employee.user')
            ->get();

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.logbook_report', [
                'report' => $report,
                'employee' => $employee,
                'people' => $people,
                'project' => $project,
                'vehicle' => $vehicle,
                'start' => $start,
                'end' => $end
            ])
            ->download($fileName);
    }
}
