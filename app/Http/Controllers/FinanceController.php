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
        $groupData = null;

        $groupTotals = Finances::getGroupTotals();

        if(isset($validatedData['group'])) {
            $currentFinanceGroup = FinanceGroup::find($validatedData['group']);
            $groupData = Finances::getGroupData($currentFinanceGroup);
        }

        $financeGroups = FinanceGroup::order()->get();


        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return view('finance.index')
            ->with('groupTotals', $groupTotals)
            ->with('financeGroups', $financeGroups->toJson())
            ->with('currentFinanceGroup', $currentFinanceGroup)
            ->with('groupData', $groupData)
            ->with(compact('currencyUnit'));
    }

    public function download(Request $request)
    {
        $this->authorize('finances-createpdf');

        $today = Carbon::today()->format('Y-m-d');

        $fileName = "FI ${today}.pdf";

        $report = Finances::getGroupReportData();
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
