<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationSettingsUpdateGeneralRequest;
use App\Models\ApplicationSettings;
use App\Models\Company;
use App\Models\Person;
use App\Models\WageService;
use Illuminate\Http\Request;

class ApplicationSettingsController extends Controller
{
    protected function resourceMethodsWithoutModels()
    {
        return array_merge(parent::resourceMethodsWithoutModels(), ['edit', 'updateGeneral']);
    }

    public function __construct()
    {
        $this->middleware('can:application-settings-update');
    }

    public function edit(Request $request)
    {
        switch ($request->tab) {
            case 'general':
                $applicationSettings = ApplicationSettings::get();

                $currentCompany = $applicationSettings->company ?? null;
                $companies = Company::order()->get();

                $currentAllowancesService = $applicationSettings->allowancesService ?? null;
                $currentOvertime50Service = $applicationSettings->overtime50Service ?? null;
                $currentOvertime100Service = $applicationSettings->overtime100Service ?? null;
                $currentTimeBalanceService = $applicationSettings->timeBalanceService ?? null;
                $currentHolidayService = $applicationSettings->holidayService ?? null;
                $wageServices = WageService::order()->get();

                $wageServiceUnits = WageService::distinct('unit')->pluck('unit');
                $currentServicesHourUnit = $applicationSettings->services_hour_unit;

                $currentSignatureNotifyPerson = optional($applicationSettings->signatureNotifyUser)->employee->person ?? null;
                $userPeople = Person::whereHas('employee', function ($query) {
                    return $query->has('user');
                })->get();

                return view('application_settings.edit_general')
                    ->with('applicationSettings', $applicationSettings)
                    ->with('currentCompany', $currentCompany)
                    ->with('companies', $companies->toJson())
                    ->with('currentAllowancesService', $currentAllowancesService)
                    ->with('currentOvertime50Service', $currentOvertime50Service)
                    ->with('currentOvertime100Service', $currentOvertime100Service)
                    ->with('currentTimeBalanceService', $currentTimeBalanceService)
                    ->with('currentHolidayService', $currentHolidayService)
                    ->with('wageServices', $wageServices->toJson())
                    ->with('wageServiceUnits', $wageServiceUnits->toJson())
                    ->with('currentServicesHourUnit', $currentServicesHourUnit)
                    ->with('currentSignatureNotifyPerson', $currentSignatureNotifyPerson)
                    ->with('userPeople', $userPeople->toJson());
            default:
                return redirect()->route('application-settings.edit', ['tab' => 'general']);
        }
    }

    public function updateGeneral(ApplicationSettingsUpdateGeneralRequest $request)
    {
        $validatedData = $request->validated();

        $settings = ApplicationSettings::firstOrCreate();

        $settings->update($validatedData);

        if(!$request->filled('holiday_service_id')) {
            $settings->holidayService()->disassociate();
            $settings->save();
        }

        ApplicationSettings::refreshCache();

        return redirect()->route('application-settings.edit', ['tab' => 'general'])->with('success', 'Die Einstellungen erfolgreich bearbeitet.');
    }
}
