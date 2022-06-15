<?php

namespace App\Models;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServiceReportService extends Model
{
    use OrdersResults;


    protected $primaryKey = null;
    public $incrementing = false;

    protected $casts = [
        'provided_on' => 'date',
        'hours' => 'double',
        'kilometres' => 'int',
    ];

    protected $fillable = [
        'provided_on', 'hours', 'kilometres', 'service_report_id',
    ];

    protected $orderKeys = [
        'default' => ['provided_on'],
    ];

    protected $touches = [
        'serviceReport'
    ];

    public function serviceReport()
    {
        return $this->belongsTo(ServiceReport::class);
    }

    public static function getServicesForAccounting(array $accountingIds): array
    {
        $accounting = DB::table('accounting')
            ->selectRaw('service_provided_on as date, sum(amount) as hours, group_concat(distinct comment separator "\n") as comment, project_id')
            ->whereIn('id', $accountingIds)
            ->groupBy('service_provided_on')
            ->groupBy('project_id')
            ->get();

        if($accounting->pluck('project_id')->unique()->count() > 1) {
            throw new Exception('Ein Servicebericht kann nur mit Leistungen und Fahrten aus einem Projekte erstellt werden.');
        }

        $project = Project::find($accounting->first()->project_id);

        $logbook = DB::table('logbook')
            ->selectRaw('driven_on as date, sum(driven_kilometres) as driven_kilometres')
            ->whereIn('driven_on', $accounting->pluck('date'))
            ->where('project_id', $project->id)
            ->groupBy('driven_on')
            ->get();

        $accounting = $accounting->map(function($accounting) {
            return (array) $accounting;
        })->keyBy('date');
        $logbook = $logbook->map(function($logbook) {
            return (array) $logbook;
        })->keyBy('date');
        $services = $accounting->mergeRecursive($logbook);

        $serviceReportServices = collect();

        foreach($services as $date => $service) {
            $serviceReportServices->push(ServiceReportService::make([
                'provided_on' => $date,
                'hours' => $service['hours'] ?? 0,
                'kilometres' => $service['driven_kilometres'] ?? 0,
            ]));
        }

        $comment = $accounting->pluck('comment')->join("\n\n");

        return [$project, $serviceReportServices, $comment];
    }

    public static function getServicesForLogbook(array $logbookIds): array
    {
        $logbook = DB::table('logbook')
            ->selectRaw('driven_on as date, sum(driven_kilometres) as driven_kilometres, project_id')
            ->whereIn('id', $logbookIds)
            ->groupBy('driven_on')
            ->groupBy('project_id')
            ->get();

        if($logbook->pluck('project_id')->unique()->count() > 1) {
            throw new Exception('Ein Servicebericht kann nur mit Leistungen und Fahrten aus einem Projekte erstellt werden.');
        }

        $project = Project::find($logbook->first()->project_id);

        $accounting = DB::table('accounting')
            ->selectRaw('service_provided_on as date, sum(amount) as hours, group_concat(distinct comment separator "\n") as comment')
            ->whereBetween('service_provided_on', [$logbook->min('date'), $logbook->max('date')])
            ->where('project_id', $project->id)
            ->groupBy('service_provided_on')
            ->get();

        $accounting = $accounting->map(function($accounting) {
            return (array) $accounting;
        })->keyBy('date');
        $logbook = $logbook->map(function($logbook) {
            return (array) $logbook;
        })->keyBy('date');
        $services = $accounting->mergeRecursive($logbook);

        $serviceReportServices = collect();

        foreach($services as $date => $service) {
            $serviceReportServices->push(ServiceReportService::make([
                'provided_on' => $date,
                'hours' => $service['hours'] ?? 0,
                'kilometres' => $service['driven_kilometres'] ?? 0,
            ]));
        }

        $comment = $accounting->pluck('comment')->join("\n\n");

        return [$project, $serviceReportServices, $comment];
    }
}
