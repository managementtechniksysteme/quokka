<?php

use App\Models\AdditionsReport;
use App\Models\Address;
use App\Models\Company;
use App\Models\ConstructionReport;
use App\Models\Employee;
use App\Models\InspectionReport;
use App\Models\MaterialService;
use App\Models\Memo;
use App\Models\Person;
use App\Models\Project;
use App\Models\ServiceReport;
use App\Models\Task;
use App\Models\Vehicle;
use App\Models\WageService;

return [

    /*
    |--------------------------------------------------------------------------
    | Searchable Models
    |--------------------------------------------------------------------------
    |
    | Define which models are considered while invoking a global search.
    | The model must implement the FiltersGlobalSearch interface in order to
    | provide search results in the form of a collection of GlobalSearchResult.
    |
    */

    'models' => [
        AdditionsReport::class,
        Address::class,
        Company::class,
        ConstructionReport::class,
        Employee::class,
        InspectionReport::class,
        MaterialService::class,
        Memo::class,
        Person::class,
        Project::class,
        ServiceReport::class,
        Task::class,
        Vehicle::class,
        WageService::class,
    ],
];
