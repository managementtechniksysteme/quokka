<?php

namespace App\Http\Resources;

use App\Models\AdditionsReport;
use App\Models\ApplicationSettings;
use App\Models\ConstructionReport;
use App\Models\FlowMeterInspectionReport;
use App\Models\InspectionReport;
use App\Models\ServiceReport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin \App\Models\Employee */
class DashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'due_soon_tasks' => $this->due_soon_tasks,
            'due_soon_tasks_involved_in' => $this->due_soon_tasks_involved_in,
            'due_soon_tasks_responsible_for' => $this->due_soon_tasks_responsible_for,
            'overdue_tasks' => $this->overdue_tasks,
            'overdue_tasks_involved_in' => $this->overdue_tasks_involved_in,
            'overdue_tasks_responsible_for' => $this->overdue_tasks_responsible_for,
            'mtd_allowances' => $this->m_t_d_allowances,
            'mtd_allowances_in_currency' => $this->m_t_d_allowances_in_currency,
            'currency_unit' => ApplicationSettings::get()->currency_unit,
            'mtd_company_kilometres' => $this->m_t_d_company_kilometres,
            'mtd_created_tasks' => $this->m_t_d_created_tasks,
            'mtd_created_tasks_involved_in' => $this->m_t_d_created_tasks_involved_in,
            'mtd_created_tasks_responsible_for' => $this->m_t_d_created_tasks_responsible_for,
            'mtd_finished_tasks' => $this->m_t_d_finished_tasks,
            'mtd_finished_tasks_involved_in' => $this->m_t_d_finished_tasks_involved_in,
            'mtd_finished_tasks_responsible_for' => $this->m_t_d_finished_tasks_responsible_for,
            'mtd_hourly_based_services' => $this->m_t_d_hourly_based_services,
            'mtd_kilometres' => $this->m_t_d_kilometres,
            'new_additions_reports' => $this->new_additions_reports,
            'mtd_new_additions_reports' => $this->m_t_d_new_additions_reports,
            'new_construction_reports' => $this->new_construction_reports,
            'mtd_new_construction_reports' => $this->m_t_d_new_construction_reports,
            'new_flow_meter_inspection_reports' => $this->new_flow_meter_inspection_reports,
            'mtd_new_flow_meter_inspection_reports' => $this->m_t_d_new_flow_meter_inspection_reports,
            'new_inspection_reports' => $this->new_inspection_reports,
            'mtd_new_inspection_reports' => $this->m_t_d_new_inspection_reports,
            'new_service_reports' => $this->new_service_reports,
            'mtd_new_service_reports' => $this->m_t_d_new_service_reports,
            'mtd_overtime' => $this->m_t_d_overtime,
            'mtd_overtime_50' => $this->m_t_d_overtime50,
            'mtd_overtime_100' => $this->m_t_d_overtime100,
            'mtd_private_kilometres' => $this->m_t_d_private_kilometres,
            'mtd_private_kilometres_in_currency' => $this->m_t_d_private_kilometres_in_currency,
            'holidays' => $this->holidays,
        ];

        if(Auth::user()->can('additions-reports.view.own') && Auth::user()->can('additions-reports.view.involved')) {
            $data['new_additions_reports_involved_in'] = $this->new_additions_reports_involved_in;
        }
        if(
            Auth::user()->can('additions-reports.view.own') &&
            Auth::user()->can('additions-reports.view.involved') &&
            Auth::user()->can('additions-reports.view.other'))
        {
            $data['new_additions_reports_total'] = AdditionsReport::newAdditionsReports();
        }
        if(Auth::user()->can('construction-reports.view.own') && Auth::user()->can('construction-reports.view.involved')) {
            $data['new_construction_reports_involved_in'] = $this->new_construction_reports_involved_in;
        }
        if(
            Auth::user()->can('construction-reports.view.own') &&
            Auth::user()->can('construction-reports.view.involved') &&
            Auth::user()->can('construction-reports.view.other'))
        {
            $data['new_construction_reports_total'] = ConstructionReport::newConstructionReports();
        }
        if(Auth::user()->can('flow-meter-inspection-reports.view.own') && Auth::user()->can('flow-meter-inspection-reports.view.other')) {
            $data['new_flow_meter_inspection_reports_total'] = FlowMeterInspectionReport::newFlowMeterInspectionReports();
        }
        if(Auth::user()->can('inspection-reports.view.own') && Auth::user()->can('inspection-reports.view.other')) {
            $data['new_inspection_reports_total'] = InspectionReport::newInspectionReports();
        }
        if(Auth::user()->can('service-reports.view.own') && Auth::user()->can('service-reports.view.other')) {
            $data['new_service_reports_total'] = ServiceReport::newServiceReports();
        }

        if(Auth::user()->can('additions-reports.approve')) {
            $data['signed_additions_reports'] = AdditionsReport::signedAdditionsReports();
            $data['mtd_signed_additions_reports'] = AdditionsReport::mtdSignedAdditionsReports();
        }
        if(Auth::user()->can('construction-reports.approve')) {
            $data['signed_construction_reports'] = ConstructionReport::signedConstructionReports();
            $data['mtd_signed_construction_reports'] = ConstructionReport::mtdSignedConstructionReports();
        }
        if(Auth::user()->can('flow-meter-inspection-reports.approve')) {
            $data['signed_flow_meter_inspection_reports'] = FlowMeterInspectionReport::signedFlowMeterInspectionReports();
            $data['mtd_signed_flow_meter_inspection_reports'] = FlowMeterInspectionReport::mtdSignedFlowMeterInspectionReports();
        }
        if(Auth::user()->can('inspection-reports.approve')) {
            $data['signed_inspection_reports'] = InspectionReport::signedInspectionReports();
            $data['mtd_signed_inspection_reports'] = InspectionReport::mtdSignedInspectionReports();
        }
        if(Auth::user()->can('service-reports.approve')) {
            $data['signed_service_reports'] = ServiceReport::signedServiceReports();
            $data['mtd_signed_service_reports'] = ServiceReport::mtdSignedServiceReports();
        }

        return  $data;
    }
}
