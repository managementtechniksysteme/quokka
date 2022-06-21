<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CommentMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public TaskComment $comment;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TaskComment $comment)
    {
        $this->comment = $comment;
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
        return (new MailMessage)
                    ->subject('Du wurdst in einem Kommentar erwähnt (Aufgabe '.$this->comment->task->name.')')
                    ->markdown('emails.comment.notification_mention', ['comment' => $this->comment]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Du wurdst in einem Kommentar erwähnt')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Du wurdest in einem Kommentar der Aufgabe '.$this->comment->task->name.' (Projekt '.$this->comment->task->project->name.') erwähnt.')
            ->tag(Task::class.':'.$this->comment->task->id.'-'.CommentMentionNotification::class)
            ->data(['url' => route('tasks.show', $this->comment->task)])
            ->vibrate($this->vibrationDuration);
    }
}
