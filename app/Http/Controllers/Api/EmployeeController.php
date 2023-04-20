<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SelectOptionCollection;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {

    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

    public function selectOptions() {
        $employees = Employee::with('person')
            ->get()
            ->each(function ($employee) {
                $employee->id = $employee->person_id;
                $employee->text = $employee->person->name;
            })
            ->sortBy('text')
            ->values();

        return new SelectOptionCollection($employees);
    }
}
