<?php

namespace App\Http\Controllers;

use App\Helpers\Finances;
use App\Http\Requests\FinanceIndexRequest;
use App\Models\ApplicationSettings;
use App\Models\FinanceGroup;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ZsgsDesign\PDFConverter\Latex;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FinanceIndexRequest $request)
    {
        $this->authorize('finances-view');

        $validatedData = $request->validated();

        $currentFinanceGroup = null;
        $currentProject = null;
        $groupData = null;

        $currentlyOpenProjectsData = Finances::getCurrentlyOpenProjectsData();
        $preExecutionProjectsData = Finances::getPreExecutionProjectsData();

        if(isset($validatedData['group'])) {
            $currentFinanceGroup = FinanceGroup::find($validatedData['group']);
            $groupData = Finances::getGroupData($currentFinanceGroup);
            $currentFinanceGroup = [
                'id' => $currentFinanceGroup->id,
                'title_string' => $currentFinanceGroup->title_string,
                'type' => 'group',
            ];
        }
        elseif(isset($validatedData['project'])) {
            $currentProject = Project::find($validatedData['project']);
            $groupData = Finances::getProjectData($currentProject);
            $currentFinanceGroup = [
                'id' => $currentProject->id,
                'title_string' => $currentProject->name,
                'type' => 'project',
            ];
        }

        $financeGroups = FinanceGroup::select('id', 'title', 'project_id')
            ->selectRaw('"group" as type')
            ->with('project')
            ->get()
            ->map
            ->only('id', 'title_string', 'type')
            ->values();

        $financeProjects = Project::where('include_in_finances', true)
            ->select('id', 'name as title_string')
            ->selectRaw('"project" as type')
            ->get()
            ->map
            ->only('id', 'title_string', 'type')
            ->values();

        $financeGroups = $financeGroups->merge($financeProjects)
            ->sortBy('title_string')
            ->values();


        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return view('finance.index')
            ->with('currentlyOpenProjectsData', $currentlyOpenProjectsData)
            ->with('preExecutionProjectsData', $preExecutionProjectsData)
            ->with('financeGroups', $financeGroups->toJson())
            ->with('currentFinanceGroup', json_encode($currentFinanceGroup))
            ->with('groupData', $groupData)
            ->with(compact('currencyUnit'));
    }

    public function download(Request $request)
    {
        $this->authorize('finances-createpdf');

        $today = Carbon::today()->format('Y-m-d');

        $fileName = "FL ${today}.pdf";

        $report = Finances::getReportData();
        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.finance_report', [
                'report' => $report,
                'currencyUnit' => $currencyUnit,
            ])
            ->download($fileName);
    }
}
