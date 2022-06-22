<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\TaskComment;
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

class CommentInvolvedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public TaskComment $comment;
    public bool $isNew;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TaskComment $comment, bool $isNew, User $user, bool $notifySelf)
    {
        $this->comment = $comment;
        $this->isNew = $isNew;
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
            'model' => TaskComment::class,
            'type' => 'TaskComment',
            'id' => $this->comment->id,
            'created' => $this->isNew,
            'route' => route('tasks.show', $this->comment->task->id),
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
            $subject = 'Es wurde ein Kommentar in einer Aufgabe erstellt, an der du beteiligt bist (Projekt '.$this->comment->task->project->name.')';
        } else {
            $subject = 'Es wurde ein Kommentar in einer Aufgabe bearbeitet, an der du beteiligt bist (Projekt '.$this->comment->task->project->name.')';
        }

        return (new MailMessage)
                    ->subject($subject)
                    ->markdown('emails.comment.notification_involved', ['comment' => $this->comment, 'isNew' => $this->isNew]);
    }

    public function toWebPush($notifiable, $notification)
    {
        if ($this->isNew) {
            $title = 'Ein Kommentar in einer Aufgabe wurde erstellt';
            $body = 'Ein Kommentar in der Aufgabe '.$this->comment->task->name.' (Projekt '.$this->comment->task->project->name.'), an der du beteiligt bist, wurde erstellt.';
        } else {
            $title = 'Ein Kommentar in einer Aufgabe wurde bearbbeitet';
            $body = 'Ein Kommentar in der Aufgabe '.$this->comment->task->name.' (Projekt '.$this->comment->task->project->name.'), an der du beteiligt bist, wurde bearbeitet.';
        }

        return (new WebPushMessage)
            ->title($title)
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body($body)
            ->tag(Task::class.':'.$this->comment->task->id.'-'.CommentInvovledNotification::class)
            ->data(['url' => route('tasks.show', $this->comment->task)])
            ->vibrate($this->vibrationDuration);
    }
}
