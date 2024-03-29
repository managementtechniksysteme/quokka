<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use App\Traits\TargetsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TaskMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public Task $task;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task, User $user, bool $notifySelf)
    {
        $this->task = $task;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            DatabaseChannel::class,
            MailChannel::class,
            WebPushChannel::class,
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'model' => Task::class,
            'type' => 'Task',
            'id' => $this->task->id,
            'route' => route('tasks.show', $this->task->id),
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Du wurdest in einer Aufgabe erwähnt (Projekt '.$this->task->project->name.')')
            ->markdown('emails.task.notification_mention', ['task' => $this->task]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Du wurdest in einer Aufgabe erwähnt')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Du wurdest in der Aufgabe '.$this->task->name.' (Projekt '.$this->task->project->name.') erwähnt')
            ->tag(Task::class.':'.$this->task->id)
            ->data(['url' => route('tasks.show', $this->task)])
            ->vibrate($this->vibrationDuration);
    }
}
