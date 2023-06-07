<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinanceRecordStoreRequest;
use App\Http\Requests\FinanceRecordUpdateRequest;
use App\Models\ApplicationSettings;
use App\Models\FinanceGroup;
use App\Models\FinanceRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FinanceRecordController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(FinanceRecord::class, 'finance_record');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FinanceGroup $financeGroup)
    {
        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return view('finance_record.create')
            ->with(compact('financeGroup'))
            ->with('financeRecord', null)
            ->with(compact('currencyUnit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinanceGroup $financeGroup
     * @return \Illuminate\Http\Response
     */
    public function store(FinanceRecordStoreRequest $request, FinanceGroup $financeGroup)
    {
        $financeRecord = FinanceRecord::make($request->validated());

        $financeRecord->financeGroup()->associate($financeGroup);

        $financeRecord->save();

        return redirect()
            ->route('finance-records.show', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord])
            ->with('success', 'Der Finanzeintrag wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FinanceGroup  $financeGroup
     * @param  \App\Models\FinanceRecord  $financeRecord
     * @return \Illuminate\Http\Response
     */
    public function show(FinanceGroup $financeGroup, FinanceRecord $financeRecord)
    {
        $financeRecord->load('financeGroup');

        return view('finance_record.show')->with(compact('financeRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FinanceGroup  $financeGroup
     * @param  \App\Models\FinanceRecord  $financeRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(FinanceGroup $financeGroup, FinanceRecord $financeRecord)
    {
        $financeRecord->load('financeGroup');

        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return view('finance_record.edit')
            ->with(compact('financeRecord'))
            ->with(compact('currencyUnit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinanceGroup  $financeGroup
     * @param  \App\Models\FinanceRecord  $financeRecord
     * @return \Illuminate\Http\Response
     */
    public function update(FinanceRecordUpdateRequest $request, FinanceGroup $financeGroup, FinanceRecord $financeRecord)
    {
        $financeRecord->update($request->validated());

        return redirect()
            ->route('finance-records.show', ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord])
            ->with('success', 'Der Finanzeintrag wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinanceGroup  $financeGroup
     * @param  \App\Models\FinanceRecord  $financeRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FinanceGroup $financeGroup, FinanceRecord $financeRecord)
    {
        $financeRecord->delete();

        return $this->getConditionalRedirect($request->redirect, $financeRecord)
        ->with('success', 'Der Finanzeintrag wurde erfolgreich entfernt.');
    }

    private function getConditionalRedirect(?string $target, FinanceRecord $financeRecord): RedirectResponse
    {
        switch ($target) {
            case 'project':
                $route = 'projects.show';
                $parameters = ['project' => $financeRecord->financeGroup->project, 'tab' => 'finances'];
                break;
            case 'show':
                $route = 'finance-records.show';
                $parameters = ['finance_group' => $financeRecord->financeGroup, 'finance_record' => $financeRecord];
                break;
            default:
                $route = 'finance-groups.show';
                $parameters = ['finance_group' => $financeRecord->financeGroup];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
