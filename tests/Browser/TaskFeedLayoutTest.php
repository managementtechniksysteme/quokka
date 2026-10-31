<?php

namespace Tests\Browser;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;

// A comment's header holds a kebab menu and an activity's doesn't; the two once
// ended up with different heights, so the activity's name sat above the centre
// of its avatar while the comment's name was centred.
test('names in comments and activities line up with the centre of their avatar on desktop', function () {
    $user = User::factory()->create();

    foreach (['tasks.view.responsible', 'tasks.update.responsible', 'tasks.create', 'tasks.comments.update.own', 'tasks.comments.delete.own'] as $permission) {
        grantPermission($user, $permission);
    }

    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'priority' => 'low']);
    TaskComment::factory()->create(['task_id' => $task->id, 'employee_id' => $user->employee_id, 'comment' => 'Ein Kommentar']);

    $this->actingAs($user);
    $this->patchJson(route('tasks.quick-update', $task), ['priority' => 'high'])->assertOk();

    $offsets = visit(route('tasks.show', $task))->on()->desktop()->script(<<<'JS'
        Array.from(document.querySelectorAll('.q-feed .q-comment')).map(function (entry) {
            var avatar = entry.querySelector(':scope > .q-avatar').getBoundingClientRect();
            var author = entry.querySelector('.q-comment__author').getBoundingClientRect();

            return Math.abs((avatar.top + avatar.height / 2) - (author.top + author.height / 2));
        })
    JS);

    expect($offsets)->toHaveCount(2);

    foreach ($offsets as $offset) {
        expect($offset)->toBeLessThan(4);
    }
});
