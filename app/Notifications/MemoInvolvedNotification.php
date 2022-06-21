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

class MemoInvolvedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Memo $memo;
    public bool $isNew;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Memo $memo, bool $isNew)
    {
        $this->memo = $memo;
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
            'model' => Memo::class,
            'type' => 'Memo',
            'id' => $this->memo->id,
            'created' => $this->isNew,
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
        if ($this->isNew) {
            $subject = 'Es wurde ein Aktenvermerk erstellt, an dem du beteiligt bist (Projekt '.$this->memo->project->name.' #'.$this->memo->number.')';
        } else {
            $subject = 'Es wurde ein Aktenvermerk bearbeitet, an dem du beteiligt bist (Projekt '.$this->memo->project->name.' #'.$this->memo->number.')';
        }

        return (new MailMessage)
                    ->subject($subject)
                    ->markdown('emails.memo.notification_involved', ['memo' => $this->memo, 'isNew' => $this->isNew]);
    }

    public function toWebPush($notifiable, $notification)
    {
        if ($this->isNew) {
            $title = 'Ein Aktenvermerk wurde erstellt';
            $body = 'Der Aktenvermerk '.$this->memo->title.',  an dem du beteiligt bist, wurde erstellt (Projekt '.$this->memo->project->name.' #'.$this->memo->number.').';
        } else {
            $title = 'Ein Aktenvermerk wurde bearbbeitet';
            $body = 'Der Aktenvermerk '.$this->memo->title.',  an dem du beteiligt bist, wurde bearbeitet (Projekt '.$this->memo->project->name.' #'.$this->memo->number.').';
        }

        return (new WebPushMessage)
            ->title($title)
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body($body)
            ->tag(Memo::class.':'.$this->memo->id)
            ->data(['url' => route('memos.show', $this->memo)])
            ->vibrate($this->vibrationDuration);
    }
}
