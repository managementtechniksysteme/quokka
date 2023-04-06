<?php

namespace App\Http\Controllers;

use App\Helpers\Finances;
use App\Http\Requests\ProjectControllingIndexRequest;
use App\Models\ApplicationSettings;
use App\Models\Project;
use Carbon\Carbon;

class ProjectControllingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectControllingIndexRequest $request)
    {
        $this->authorize('finances-view');

        $validatedData = $request->validated();

        $currentProject = null;
        $accountingFinanceData = null;
        $manualFinanceData = null;

        $start = isset($validatedData['start']) ? Carbon::parse($validatedData['start']) : null;
        $end = isset($validatedData['start']) ? Carbon::parse($validatedData['end']) : null;

        if(isset($validatedData['project'])) {
            $currentProject = Project::find($validatedData['project']);
            $accountingFinanceData = [
                'revenue' => $currentProject->getBilledCosts($start, $end) ?? 0,
                'expense' => -$currentProject->getCurrentCosts($start, $end),
            ];
            $manualFinanceData = Finances::getProjectData($currentProject);
        }

        $projects = Project::order()->get();

        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return view('project_controlling.index')
            ->with('projects', $projects->toJson())
            ->with('currentProject', $currentProject)
            ->with('start', $start)
            ->with('end', $end)
            ->with('accountingFinanceData', $accountingFinanceData)
            ->with('manuelFinanceData', $manualFinanceData)
            ->with(compact('currencyUnit'));
    }
}
