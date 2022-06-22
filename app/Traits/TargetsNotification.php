<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Notifications\Channels\MailChannel;
use NotificationChannels\WebPush\WebPushChannel;

trait TargetsNotification
{
    public User|null $user = null;
    public bool|null $notifySelf = null;

    public function shouldSend(mixed $notifiable, string $channel): bool
    {

        if ($notifiable instanceof User && $this->user && !$this->shouldNotifySelf($notifiable)) {
            return false;
        }

        if ($notifiable instanceof User && !$this->shouldNotifyVia($notifiable, $channel)) {
            return false;
        }

        return true;
    }

    private function shouldNotifySelf(mixed $notifiable): bool
    {
        return $this->user->employee_id === $notifiable->employee_id && $this->notifySelf;
    }

    private function shouldNotifyVia(mixed $notifiable, string $channel): bool
    {
        if($channel === MailChannel::class &&
            !$notifiable->notificationsViaEmail()->where('type', static::class)->exists()) {
            return false;
        }

        if($channel === WebPushChannel::class &&
            !$notifiable->notificationsViaWebPush()->where('type', static::class)->exists()) {
            return false;
        }

        return true;
    }
}
