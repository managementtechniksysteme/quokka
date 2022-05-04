<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountingDownloadRequest;
use App\Http\Requests\AccountingIndexRequest;
use App\Http\Requests\AccountingStoreRequest;
use App\Http\Requests\AccountingUpdateRequest;
use App\Models\Accounting;
use App\Models\ApplicationSettings;
use App\Models\Person;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use ZsgsDesign\PDFConverter\Latex;

class AccountingController extends Controller
{
    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'showEmail' => 'email',
            'email' => 'email',
        ]);
    }

    public function resourceMethodsWithoutModels()
    {
        return array_merge(parent::resourceMethodsWithoutModels(), ['download']);
    }

    public function __construct()
    {
        $this->authorizeResource(Accounting::class, 'accounting');
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $currentAccounting = Accounting::filterSearch(
                $request->validate((new AccountingIndexRequest($request->query()))->rules())
            )->order()->get();

            return response()->json($currentAccounting, Response::HTTP_OK);
        }

        $projects = Project::order()->get();
        $services = Service::order()->get();
        $employees = Person::has('employee')->order()->get();
        $currentEmployee = Auth::user()->employee->person;
        $servicesHourUnit = ApplicationSettings::get()->services_hour_unit ?? null;
        $minAccountingAmount = ApplicationSettings::get()->accounting_min_amount;
        $expandErrors = Auth::user()->settings->accounting_expand_errors;
        $filterDefaultDays = Auth::user()->settings->accounting_filter_default_days;
        $pageSize = Auth::user()->settings->list_pagination_size;

        $accountingPermissions = Auth::user()
            ->permissions()
            ->where('name', 'LIKE', 'accounting%')
            ->select('name')
            ->pluck('name');

        return view('accounting.index')
            ->with('currentAccounting', null)
            ->with('projects', $projects->toJson())
            ->with('services', $services->toJson())
            ->with('employees', $employees->toJson())
            ->with('currentEmployee', $currentEmployee->toJson())
            ->with('permissions', $accountingPermissions->toJson())
            ->with('servicesHourUnit', $servicesHourUnit)
            ->with('minAccountingAmount', $minAccountingAmount)
            ->with('expandErrors', json_encode($expandErrors))
            ->with('filterDefaultDays', json_encode($filterDefaultDays))
            ->with('pageSize', json_encode($pageSize));
    }


    public function store(AccountingStoreRequest $request)
    {
        $accounting = Accounting::make($request->validated());
        $accounting->employee()->associate(Auth::user()->employee);
        $accounting->save();

        return response()->json($accounting, Response::HTTP_CREATED);
    }

    public function update(AccountingUpdateRequest $request, Accounting $accounting)
    {
        $accounting->update($request->validated());

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(Accounting $accounting)
    {
        $accounting->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function download(AccountingDownloadRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::find($validatedData['employee_id'])->load('employee.person');
        $project = isset($validatedData['project_id']) ? Project::find($validatedData['project_id']) : null;
        $service = isset($validatedData['service_id']) ? Service::find($validatedData['service_id']) : null;
        $allowancesService = ApplicationSettings::get()->allowancesService;
        $holidayService = ApplicationSettings::get()->holidayService;
        $currencyUnit = ApplicationSettings::get()->currency_unit;

        $username = Str::upper($user->username);

        $start = isset($validatedData['start']) ? Carbon::parse($validatedData['start']) : null;
        $end = isset($validatedData['end']) ? Carbon::parse($validatedData['end']) : null;

        $startFormatted = optional($start)->format('Ymd') ?? '';
        $endFormatted = optional($end)->format('Ymd') ?? '';
        $rangeString = $startFormatted.($start&&$end ? '-' : '').$endFormatted;

        $report = Accounting::getReport($validatedData);

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.accounting_report', [
                'report' => $report,
                'employee' => $user->employee,
                'project' => $project,
                'service' => $service,
                'allowancesService' => $allowancesService,
                'holidayService' => $holidayService,
                'currencyUnit' => $currencyUnit,
                'start' => $start,
                'end' => $end
            ])
            ->download('AR '.$username.' '.$rangeString.'.pdf');
    }
}
