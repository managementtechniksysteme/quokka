<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationSettingsUpdateGeneralRequest;
use App\Models\ApplicationSettings;
use App\Models\Company;
use App\Models\Person;
use Illuminate\Http\Request;

class ApplicationSettingsController extends Controller
{
    public function edit(Request $request)
    {
        switch ($request->tab) {
            case 'general':
                $currentCompany = ApplicationSettings::get()->company ?? null;
                $companies = Company::order()->get();

                $currentSignatureNotifyPerson = optional(ApplicationSettings::get()->signatureNotifyUser)->employee->person ?? null;
                $userPeople = Person::whereHas('employee', function ($query) {
                    return $query->has('user');
                })->get();

                return view('application_settings.edit_general')
                    ->with('currentCompany', $currentCompany)
                    ->with('companies', $companies->toJson())
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

        return redirect()->route('application-settings.edit', ['tab' => 'general'])->with('success', 'Die Einstellungen erfolgreich bearbeitet.');
    }
}
