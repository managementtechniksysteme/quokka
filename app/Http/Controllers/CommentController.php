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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $task = Task::find($request->task);

        if (! $task) {
            abort(404);
        }

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

        $comment = TaskComment::create($validatedData);

        $comment->employee()->associate(auth()->user()->employee);

        $task = Task::find($request->task_id);
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
