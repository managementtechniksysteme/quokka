<?php

namespace App\Notifications;

use App\Models\Memo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class MemoMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Memo $memo;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Memo $memo)
    {
        $this->memo = $memo;
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
            'model' => Memo::class,
            'type' => 'Memo',
            'id' => $this->memo->id,
            'route' => route('memos.show', $this->memo->id),
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
                    ->subject('Du wurdst in einem Aktenvermerk erwähnt (Projekt '.$this->memo->project->name.' #'.$this->memo->number.')')
                    ->markdown('emails.memo.notification_mention', ['memo' => $this->memo]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Du wurdst in einem Aktenvermerk erwähnt')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Du wurdst im Aktenvermerk '.$this->memo->title.' (Projekt '.$this->memo->project->name.' #'.$this->memo->number.') erwähnt.')
            ->tag(Memo::class.':'.$this->memo->id)
            ->data(['url' => route('memos.show', $this->memo)])
            ->vibrate($this->vibrationDuration);
    }
}
