<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, DatabaseNotification $notification): bool
    {
        return $notification->notifiable_id === $user->getAuthIdentifier();
    }
}
