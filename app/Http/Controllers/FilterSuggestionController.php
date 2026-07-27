<?php

namespace App\Http\Controllers;

use App\Models\AdditionsReport;
use App\Models\Company;
use App\Models\ConstructionReport;
use App\Models\DeliveryNote;
use App\Models\Employee;
use App\Models\FlowMeterInspectionReport;
use App\Models\InspectionReport;
use App\Models\Memo;
use App\Models\Person;
use App\Models\Project;
use App\Models\ServiceReport;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class FilterSuggestionController extends Controller
{
    protected array $models = [
        'task' => Task::class,
        'person' => Person::class,
        'company' => Company::class,
        'project' => Project::class,
        'employee' => Employee::class,
        'memo' => Memo::class,
        'delivery_note' => DeliveryNote::class,
        'service_report' => ServiceReport::class,
        'inspection_report' => InspectionReport::class,
        'flow_meter_inspection_report' => FlowMeterInspectionReport::class,
        'construction_report' => ConstructionReport::class,
        'additions_report' => AdditionsReport::class,
    ];

    public function search(Request $request): JsonResponse
    {
        $modelClass = $this->models[$request->query('model', '')] ?? null;

        abort_unless($modelClass, 404);

        Gate::authorize('viewAny', $modelClass);

        $prefix = $request->query('prefix', '');
        $validLookupPrefixes = collect($modelClass::filterKeyMetadata())->where('kind', 'lookup')->pluck('prefix');

        abort_unless($validLookupPrefixes->contains($prefix), 422);

        return response()->json($modelClass::filterSuggestionValues($prefix, (string) $request->query('query', '')));
    }
}
