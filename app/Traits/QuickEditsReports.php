<?php

namespace App\Traits;

use App\Http\Requests\ReportQuickUpdateRequest;
use App\Models\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Single-field edits from a report's detail page (weather, involved employees,
 * present people), shared by the report controllers. A change behaves like the
 * full edit form: a signed report goes back to "new" and loses its signature and
 * any pending requests, and the report's updated event notifies the people involved.
 */
trait QuickEditsReports
{
    /**
     * What the detail page's in-place editors need, or null for a user who may not edit the report.
     *
     * @return array<string, mixed>|null
     */
    protected function reportQuickEditData(Model $report, bool $withParticipants): ?array
    {
        if (Auth::user()->cannot('update', $report)) {
            return null;
        }

        $data = [
            'weather' => collect(['sunny' => 'sun', 'cloudy' => 'cloud', 'rainy' => 'cloud-rain', 'snowy' => 'cloud-snow'])
                ->map(fn (string $icon, string $weather) => ['value' => $weather, 'label' => __($weather), 'icon' => $icon])
                ->values()
                ->all(),
        ];

        if ($withParticipants) {
            $employees = Person::has('employee')->with('employee.user')->order()->get();
            $people = Person::order()->get();

            $data['employees'] = $employees->values();
            $data['involved'] = $employees->whereIn('id', $report->involvedEmployees->pluck('person_id'))->values();
            $data['people'] = $people->values();
            $data['present'] = $people->whereIn('id', $report->presentPeople->pluck('id'))->values();
        }

        return $data;
    }

    /**
     * @param  class-string  $updatedEvent  the report's *UpdatedEvent
     * @param  array<int, string>  $allowedFields  the fields this report type can quick-edit
     */
    protected function quickUpdateReport(ReportQuickUpdateRequest $request, Model $report, string $updatedEvent, array $allowedFields): JsonResponse
    {
        $validatedData = $request->validated();
        $field = array_key_first($validatedData);

        if (! in_array($field, $allowedFields, true)) {
            throw ValidationException::withMessages(['field' => 'Dieses Feld kann hier nicht geändert werden.']);
        }

        if ($field === 'weather') {
            $report->update(['weather' => $validatedData['weather']]);
            $changed = $report->wasChanged();
        } elseif ($field === 'involved_ids') {
            $touched = $report->involvedEmployees()->syncWithPivotValues($validatedData['involved_ids'], ['employee_type' => 'involved']);
            $changed = $touched['attached'] || $touched['detached'] || $touched['updated'];
        } elseif (! empty($validatedData['present_ids'])) {
            $touched = $report->presentPeople()->syncWithPivotValues($validatedData['present_ids'], ['person_type' => 'present']);
            $changed = $touched['attached'] || $touched['detached'] || $touched['updated'];
        } else {
            $changed = $report->presentPeople()->detach() > 0;
        }

        if ($changed) {
            $report->touch();

            if ($report->status === 'signed') {
                $report->status = 'new';
                $report->save();

                $report->deleteDownloadRequest();
                $report->deleteSignature();
            }

            $report->deleteSignatureRequest();

            event(new $updatedEvent($report, Auth::user(), Auth::user()->settings->notify_self));
        }

        return response()->json([
            'field' => $field,
            'value' => match ($field) {
                'weather' => $report->weather,
                'involved_ids' => $report->involvedEmployees()->pluck('employees.person_id')->all(),
                default => $report->presentPeople()->pluck('people.id')->all(),
            },
            'changed' => $changed,
        ]);
    }
}
