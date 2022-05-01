<?php

namespace App\Http\Controllers;

use App\Events\CommentCreatedEvent;
use App\Events\CommentUpdatedEvent;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected function resourceAbilityMap()
    {
        return array_diff(parent::resourceAbilityMap(), [
            'create' => 'create',
            'store' => 'create',
        ]);
    }

    public function __construct()
    {
        $this->authorizeResource(TaskComment::class, 'comment');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $task = Task::findOrFail($request->task);

        $this->authorize('create', [TaskComment::class, $task]);

        return view('comment.create')->with('comment', null)->with('task', $task)->with('currentAttachments', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentStoreRequest $request)
    {
        $validatedData = $request->validated();

        $task = Task::findOrFail($request->task_id);

        $this->authorize('create', [TaskComment::class, $task]);

        $comment = TaskComment::create($validatedData);

        $comment->employee()->associate(auth()->user()->employee);

        $task->comments()->save($comment);

        if ($request->new_attachments) {
            $comment->addAttachments($request->new_attachments);
        }

        event(new CommentCreatedEvent($comment));

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Der Kommentar wurde erfolgreich angelegt.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskComment $comment)
    {
        $currentAttachments = $comment->attachmentsWithUrl();

        return view('comment.edit')
            ->with('comment', $comment)
            ->with('task', $comment->task)
            ->with('currentAttachments', $currentAttachments->toJson());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(CommentUpdateRequest $request, TaskComment $comment)
    {
        $validatedData = $request->validated();

        $comment->update($validatedData);

        if ($request->remove_attachments) {
            $comment->deleteAttachments($request->remove_attachments);
        }

        if ($request->new_attachments) {
            $comment->addAttachments($request->new_attachments);
        }

        event(new CommentUpdatedEvent($comment));

        return redirect()
            ->route('tasks.show', $comment->task)
            ->with('success', 'Der Kommentar wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskComment $comment)
    {
        $comment->delete();

        return redirect()
            ->route('tasks.show', $comment->task)
            ->with('success', 'Der Kommentar wurde erfolgreich entfernt.');
    }
}
