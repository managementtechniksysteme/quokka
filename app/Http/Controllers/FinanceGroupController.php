<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinanceGroupStoreRequest;
use App\Http\Requests\FinanceGroupUpdateRequest;
use App\Models\FinanceGroup;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceGroupController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(FinanceGroup::class, 'finance_group');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $financeGroups = FinanceGroup::filterSearch($request->search)
            ->order($request->sort)
            ->withSum('financeRecords', 'amount')
            ->withCount('financeRecords')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('finance_group.index')->with(compact('financeGroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        return view('finance_group.create')
            ->with('financeGroup', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinanceGroupStoreRequest $request)
    {
        $validatedData = $request->validated();

        $financeGroup = FinanceGroup::create($validatedData);

        return redirect()->route('finance-groups.show', $financeGroup)
            ->with('success', 'Die Finanzgruppe wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FinanceGroup  $financeGroup
     * @return \Illuminate\Http\Response
     */
    public function show(FinanceGroup $financeGroup)
    {
        $financeGroup->loadSum('financeRecords', 'amount');
        $financeRecords = $financeGroup->financeRecords()
            ->order()
            ->paginate(Auth::user()->settings->list_pagination_size);

        return view('finance_group.show')
            ->with(compact('financeGroup'))
            ->with(compact('financeRecords'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FinanceGroup  $financeGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(FinanceGroup $financeGroup)
    {

        return view('finance_group.edit')
            ->with(compact('financeGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinanceGroup  $financeGroup
     * @return \Illuminate\Http\Response
     */
    public function update(FinanceGroupUpdateRequest $request, FinanceGroup $financeGroup)
    {
        $validatedData = $request->validated();

        $financeGroup->update($validatedData);

       return redirect()->route('finance-groups.show', $financeGroup)
            ->with('success', 'Die Finanzgruppe wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinanceGroup  $financeGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinanceGroup $financeGroup)
    {
        $financeGroup->financeRecords()->delete();
        $financeGroup->delete();

        return redirect()->route('finance-groups.index')
            ->with('success', 'Die Finanzgruppe wurde erfolgreich entfernt.');
    }
}
