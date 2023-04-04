<?php

namespace App\Http\Controllers;

use App\Helpers\Finances;
use App\Http\Requests\FinanceIndexRequest;
use App\Http\Requests\ProjectFinanceIndexRequest;
use App\Models\ApplicationSettings;
use App\Models\FinanceGroup;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ZsgsDesign\PDFConverter\Latex;

class ProjectFinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectFinanceIndexRequest $request)
    {
        $this->authorize('finances-view');

        $validatedData = $request->validated();

        $currentProject = null;
        $projectData = null;

        $currentlyOpenProjectsData = Finances::getCurrentlyOpenProjectsData();
        $preExecutionProjectsData = Finances::getPreExecutionProjectsData();

        if(isset($validatedData['project'])) {
            $currentProject = Project::find($validatedData['project']);
            $projectData = Finances::getProjectData($currentProject);
        }

        $projects = Project::where('include_in_finances', true)
            ->orWhereNotNull('financial_costs')
            ->order()
            ->get();


        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return view('finance.project_overview')
            ->with('currentlyOpenProjectsData', $currentlyOpenProjectsData)
            ->with('preExecutionProjectsData', $preExecutionProjectsData)
            ->with('projects', $projects->toJson())
            ->with('currentProject', $currentProject)
            ->with('projectData', $projectData)
            ->with(compact('currencyUnit'));
    }

    public function download(Request $request)
    {
        $this->authorize('finances-createpdf');

        $today = Carbon::today()->format('Y-m-d');

        $fileName = "PF ${today}.pdf";

        $report = Finances::getProjectReportData();
        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.project_finance_report', [
                'report' => $report,
                'currencyUnit' => $currencyUnit,
            ])
            ->download($fileName);
    }
}
