<?php

namespace App\Http\Controllers;

use App\Models\AdditionsReport;
use App\Models\ConstructionReport;
use App\Models\DeliveryNote;
use App\Models\FlowMeterInspectionReport;
use App\Models\InspectionReport;
use App\Models\ServiceReport;
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
            ->with('employeeNewServiceReports', Auth::user()->employee->new_service_reports)
            ->with('employeeMtdNewServiceReports', Auth::user()->employee->mtd_new_service_reports)
            ->with('newServiceReports', ServiceReport::newServiceReports())
            ->with('employeeNewAdditionsReports', Auth::user()->employee->new_additions_reports)
            ->with('employeeMtdNewAdditionsReports', Auth::user()->employee->mtd_new_additions_reports)
            ->with('employeeNewAdditionsReportsInvolvedIn', Auth::user()->employee->new_additions_reports_involved_in)
            ->with('newAdditionsReports', AdditionsReport::newAdditionsReports())
            ->with('employeeNewInspectionReports', Auth::user()->employee->new_inspection_reports)
            ->with('employeeMtdNewInspectionReports', Auth::user()->employee->mtd_new_inspection_reports)
            ->with('newInspectionReports', InspectionReport::newInspectionReports())
            ->with('employeeNewConstructionReports', Auth::user()->employee->new_construction_reports)
            ->with('employeeMtdNewConstructionReports', Auth::user()->employee->mtd_new_construction_reports)
            ->with('employeeNewConstructionReportsInvolvedIn', Auth::user()->employee->new_construction_reports_involved_in)
            ->with('newConstructionReports', ConstructionReport::newConstructionReports())
            ->with('employeeNewFlowMeterInspectionReports', Auth::user()->employee->new_flow_meter_inspection_reports)
            ->with('employeeMtdNewFlowMeterInspectionReports', Auth::user()->employee->mtd_new_flow_meter_inspection_reports)
            ->with('newFlowMeterInspectionReports', FlowMeterInspectionReport::newFlowMeterInspectionReports())
            ->with('signedServiceReports', ServiceReport::signedServiceReports())
            ->with('mtdSignedServiceReports', ServiceReport::mtdSignedServiceReports())
            ->with('signedAdditionsReports', AdditionsReport::signedAdditionsReports())
            ->with('mtdSignedAdditionsReports', AdditionsReport::mtdSignedAdditionsReports())
            ->with('signedInspectionReports', InspectionReport::signedInspectionReports())
            ->with('mtdSignedInspectionReports', InspectionReport::mtdSignedInspectionReports())
            ->with('signedConstructionReports', ConstructionReport::signedConstructionReports())
            ->with('mtdSignedConstructionReports', ConstructionReport::mtdSignedConstructionReports())
            ->with('signedFlowMeterInspectionReports', FlowMeterInspectionReport::signedFlowMeterInspectionReports())
            ->with('mtdSignedFlowMeterInspectionReports', FlowMeterInspectionReport::mtdSignedFlowMeterInspectionReports())
            ->with('newDeliveryNotes', DeliveryNote::newDeliveryNotes())
            ->with('mtdNewDeliveryNotes', DeliveryNote::mtdNewDeliveryNotes())
            ->with('signedDeliveryNotes', DeliveryNote::signedDeliveryNotes())
            ->with('mtdSignedDeliveryNotes', DeliveryNote::mtdSignedDeliveryNotes());
    }
}
