<?php

namespace App\Http\Controllers;

use App\Models\AdditionsReport;
use App\Models\ConstructionReport;
use App\Models\DeliveryNote;
use App\Models\FlowMeterInspectionReport;
use App\Models\InspectionReport;
use App\Models\ServiceReport;
use App\Support\GlobalSearch\GlobalSearch;
use Illuminate\Http\Request;
     use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Auth::user()->employee->isCurrentlyOnHoliday() && ! $request->has('skip-holiday')) {
            return view('holiday');
        }

        $user = Auth::user();

        $reportRows = [
            [
                'ab' => 'SB', 'name' => 'Serviceberichte',
                'route' => route('service-reports.index', ['search' => 'ist:neu']),
                'offen' => $user->employee->new_service_reports,
                'erledigbar' => $user->can('service-reports.approve') ? ServiceReport::signedServiceReports() : null,
                'gesamt' => ($user->can('service-reports.view.own') && $user->can('service-reports.view.other')) ? ServiceReport::newServiceReports() : null,
                'show' => true,
            ],
            [
                'ab' => 'RB', 'name' => 'Regieberichte',
                'route' => route('additions-reports.index', ['search' => 'ist:neu']),
                'offen' => $user->employee->new_additions_reports,
                'erledigbar' => $user->can('additions-reports.approve') ? AdditionsReport::signedAdditionsReports() : null,
                'gesamt' => ($user->can('additions-reports.view.own') && $user->can('additions-reports.view.involved') && $user->can('additions-reports.view.other')) ? AdditionsReport::newAdditionsReports() : null,
                'show' => true,
            ],
            [
                'ab' => 'PB', 'name' => 'Prüfberichte',
                'route' => route('inspection-reports.index', ['search' => 'ist:neu']),
                'offen' => $user->employee->new_inspection_reports,
                'erledigbar' => $user->can('inspection-reports.approve') ? InspectionReport::signedInspectionReports() : null,
                'gesamt' => ($user->can('inspection-reports.view.own') && $user->can('inspection-reports.view.other')) ? InspectionReport::newInspectionReports() : null,
                'show' => true,
            ],
            [
                'ab' => 'DM', 'name' => 'Prüfberichte Durchflussmesseinrichtungen',
                'route' => route('flow-meter-inspection-reports.index', ['search' => 'ist:neu']),
                'offen' => $user->employee->new_flow_meter_inspection_reports,
                'erledigbar' => $user->can('flow-meter-inspection-reports.approve') ? FlowMeterInspectionReport::signedFlowMeterInspectionReports() : null,
                'gesamt' => ($user->can('inspection-reports.view.own') && $user->can('flow-meter-inspection-reports.view.other')) ? FlowMeterInspectionReport::newFlowMeterInspectionReports() : null,
                'show' => true,
            ],
            [
                'ab' => 'BT', 'name' => 'Bautagesberichte',
                'route' => route('construction-reports.index', ['search' => 'ist:neu']),
                'offen' => $user->employee->new_construction_reports,
                'erledigbar' => $user->can('construction-reports.approve') ? ConstructionReport::signedConstructionReports() : null,
                'gesamt' => ($user->can('construction-reports.view.own') && $user->can('construction-reports.view.involved') && $user->can('construction-reports.view.other')) ? ConstructionReport::newConstructionReports() : null,
                'show' => true,
            ],
            [
                'ab' => 'LI', 'name' => 'Lieferscheine',
                'route' => route('delivery-notes.index', ['search' => 'ist:neu']),
                'offen' => null,
                'erledigbar' => $user->can('delivery-notes.approve') ? DeliveryNote::signedDeliveryNotes() : null,
                'gesamt' => DeliveryNote::newDeliveryNotes(),
                'show' => $user->can('viewAny', DeliveryNote::class),
                'accent' => true,
            ],
        ];

        $reportRows = array_values(array_filter($reportRows, fn ($r) => $r['show']));
        $totalErledigbar = collect($reportRows)->sum(fn ($r) => $r['erledigbar'] ?? 0);

        return view('home')
            ->with('employeeMtdHourlyBasedServices', Auth::user()->employee->mtd_hourly_based_services)
            ->with('employeeMtdAllowances', Auth::user()->employee->mtd_allowances)
            ->with('employeeMtdAllowancesInCurrency', Auth::user()->employee->mtd_allowances_in_currency)
            ->with('employeeMtdOvertime', Auth::user()->employee->mtd_overtime)
            ->with('employeeMtdOvertime50', Auth::user()->employee->mtd_overtime_50)
            ->with('employeeMtdOvertime100', Auth::user()->employee->mtd_overtime_100)
            ->with('employeeMtdKilometres', Auth::user()->employee->mtd_kilometres)
            ->with('employeeMtdCompanyKilometres', Auth::user()->employee->mtd_company_kilometres)
            ->with('employeeMtdPrivateKilometres', Auth::user()->employee->mtd_private_kilometres)
            ->with('employeeMtdPrivateKilometresInCurrency', Auth::user()->employee->mtd_private_kilometres_in_currency)
            ->with('employeeHolidays', Auth::user()->employee->holidays)
            ->with('employeeMtdCreatedTasks', Auth::user()->employee->mtd_created_tasks)
            ->with('employeeMtdCreatedTasksResponsibleFor', Auth::user()->employee->mtd_created_tasks_responsible_for)
            ->with('employeeMtdCreatedTasksInvolvedIn', Auth::user()->employee->mtd_created_tasks_involved_in)
            ->with('employeeMtdFinishedTasks', Auth::user()->employee->mtd_finished_tasks)
            ->with('employeeMtdFinishedTasksResponsibleFor', Auth::user()->employee->mtd_finished_tasks_responsible_for)
            ->with('employeeMtdFinishedTasksInvolvedIn', Auth::user()->employee->mtd_finished_tasks_involved_in)
            ->with('employeeOverdueTasks', Auth::user()->employee->overdue_tasks)
            ->with('employeeOverdueTasksResponsibleFor', Auth::user()->employee->overdue_tasks_responsible_for)
            ->with('employeeOverdueTasksInvolvedIn', Auth::user()->employee->overdue_tasks_involved_in)
            ->with('employeeDueSoonTasks', Auth::user()->employee->due_soon_tasks)
            ->with('employeeDueSoonTasksResponsibleFor', Auth::user()->employee->due_soon_tasks_responsible_for)
            ->with('employeeDueSoonTasksInvolvedIn', Auth::user()->employee->due_soon_tasks_involved_in)
            ->with('reportRows', $reportRows)
            ->with('totalErledigbar', $totalErledigbar)
            ->with('latestChanges', Auth::user()->can('tools-viewlatestchanges') ? GlobalSearch::getLatestChanges(5) : collect());
    }
}
