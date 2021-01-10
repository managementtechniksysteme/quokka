<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CommentInvolvedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public TaskComment $comment;
    public bool $isNew;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TaskComment $comment, bool $isNew)
    {
        $this->comment = $comment;
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
            MailChannel::class,
            WebPushChannel::class,
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
