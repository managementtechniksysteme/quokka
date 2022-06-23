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
        \Illuminate\Support\Facades\Log::info('notifiable id');
        \Illuminate\Support\Facades\Log::info($notifiable->employee_id);
        \Illuminate\Support\Facades\Log::info('user id');
        \Illuminate\Support\Facades\Log::info($this->user->employee_id);
        \Illuminate\Support\Facades\Log::info('notify self');
        \Illuminate\Support\Facades\Log::info($this->notifySelf);

        if ($notifiable instanceof User && $this->user && !$this->shouldNotifyUser($notifiable)) {
            \Illuminate\Support\Facades\Log::info('not notifying self');
            return false;
        }

        if ($notifiable instanceof User && !$this->shouldNotifyVia($notifiable, $channel)) {
            return false;
        }

        return true;
    }

    private function shouldNotifyUser(User $notifiable): bool
    {
        if($this->user->employee_id === $notifiable->employee_id) {
            return $this->notifySelf;
        }
        
        return true;
    }

    private function shouldNotifyVia(User $notifiable, string $channel): bool
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
