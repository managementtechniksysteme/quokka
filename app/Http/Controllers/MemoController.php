<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemoStoreRequest;
use App\Http\Requests\MemoUpdateRequest;
use App\Models\Memo;
use App\Models\Person;
use App\Models\Project;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $memos = Memo::order($request->input())
            ->with('employeeComposer.person')
            ->with('personRecipient')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('memo.index')->with(compact('memos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $currentProject = null;

        if ($request->filled('project')) {
            $currentProject = Project::find($request->project);
        }

        $projects = Project::order()->get();
        $employees = Person::has('employee')->order()->get();
        $people = Person::order()->get();

        return view('memo.create')
            ->with('memo', null)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentEmployeeComposer', null)
            ->with('employees', $employees->toJson())
            ->with('currentPersonRecipient', null)
            ->with('currentPresentPeople', null)
            ->with('currentNotifiedPeople', null)
            ->with('people', $people->toJson());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemoStoreRequest $request)
    {
        $validatedData = $request->validated();

        $memo = Memo::make($validatedData);
        $memo->number = 1;

        $latestMemo = Memo::where('project_id', $request->project_id)->latest('number')->first();

        if ($latestMemo) {
            $memo->number = $latestMemo->number + 1;
        }

        $memo->save();

        if ($request->filled('present_ids')) {
            $memo->presentPeople()->attach(Person::find($request->present_ids), ['person_type' => 'present']);
        }

        if ($request->filled('notified_ids')) {
            $memo->notifiedPeople()->attach(Person::find($request->notified_ids), ['person_type' => 'notified']);
        }

        return redirect()->route('memos.index')->with('success', 'Der Aktenvermerk wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Memo  $memo
     * @return \Illuminate\Http\Response
     */
    public function show(Memo $memo)
    {
        $memo->load('project')
            ->load('employeeComposer')
            ->load('personRecipient')
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }])
            ->load(['notifiedPeople' => function ($query) {
                $query->order();
            }]);

        return view('memo.show')->with(compact('memo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Memo  $memo
     * @return \Illuminate\Http\Response
     */
    public function edit(Memo $memo)
    {
        $currentProject = $memo->project;
        $projects = Project::order()->get();

        $currentEmployeeComposer = $memo->employeeComposer->person;
        $employees = Person::has('employee')->order()->get();

        $currentPersonRecipient = $memo->personRecipient ?? null;
        $currentPresentPeople = $memo->presentPeople ?? null;
        $currentNotifiedPeople = $memo->notifiedPeople ?? null;
        $people = Person::order()->get();

        return view('memo.edit')
            ->with('memo', $memo)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentEmployeeComposer', $currentEmployeeComposer)
            ->with('employees', $employees->toJson())
            ->with('currentPersonRecipient', $currentPersonRecipient)
            ->with('currentPresentPeople', $currentPresentPeople)
            ->with('currentNotifiedPeople', $currentNotifiedPeople)
            ->with('people', $people->toJson());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Memo  $memo
     * @return \Illuminate\Http\Response
     */
    public function update(MemoUpdateRequest $request, Memo $memo)
    {
        $validatedData = $request->validated();

        $memo->update($validatedData);

        if ($request->filled('present_ids')) {
            $pivotData = array_fill(0, count($request->present_ids), ['person_type' => 'present']);
            $memo->presentPeople()->sync(array_combine($request->present_ids, $pivotData));
        } else {
            $memo->presentPeople()->detach($memo->presentPeople, ['person_type' => 'present']);
        }

        if ($request->filled('notified_ids')) {
            $pivotData = array_fill(0, count($request->notified_ids), ['person_type' => 'notified']);
            $memo->notifiedPeople()->sync(array_combine($request->notified_ids, $pivotData));
        } else {
            $memo->notifiedPeople()->detach($memo->notifiedPeople, ['person_type' => 'notified']);
        }

        return redirect()->route('memos.index')->with('success', 'Der Aktenvermerk wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Memo  $memo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Memo $memo)
    {
        $memo->presentPeople()->detach();
        $memo->notifiedPeople()->detach();
        $memo->delete();

        return redirect()->route('memos.index')->with('success', 'Die Aktenvermerk wurde erfolgreich entfernt.');
    }
}
