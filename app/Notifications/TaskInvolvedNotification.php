<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TaskInvolvedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Task $task;
    public bool $isNew;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task, bool $isNew)
    {
        $this->task = $task;
        $this->isNew = $isNew;
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
            'created' => $this->isNew,
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
        if ($this->isNew) {
            $subject = 'Es wurde eine Aufgabe erstellt, an der du beteiligt bist (Projekt '.$this->task->project->name.')';
        } else {
            $subject = 'Es wurde eine Aufgabe bearbeitet, an der du beteiligt bist (Projekt '.$this->task->project->name.')';
        }

        return (new MailMessage)
                    ->subject($subject)
                    ->markdown('emails.task.notification_involved', ['task' => $this->task, 'isNew' => $this->isNew]);
    }

    public function toWebPush($notifiable, $notification)
    {
        if ($this->isNew) {
            $title = 'Eine Aufgabe wurde erstellt';
            $body = 'Die Aufgabe '.$this->task->name.', an der du beteiligt bist, wurde erstellt (Projekt '.$this->task->project->name.').';
        } else {
            $title = 'Eine Aufgabe wurde bearbeitet';
            $body = 'Die Aufgabe '.$this->task->name.', an der du beteiligt bist, wurde bearbeitet (Projekt '.$this->task->project->name.').';
        }

        return (new WebPushMessage)
            ->title($title)
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body($body)
            ->tag(Task::class.':'.$this->task->id)
            ->data(['url' => route('tasks.show', $this->task)])
            ->vibrate($this->vibrationDuration);
    }
}
