<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\NotificationSummaryNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotificationSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Carbon $date;
    public $users;

    public function __construct()
    {
        $this->date = Carbon::yesterday();
        $this->users = User::whereHas('notifications', function ($query) {
            return $query->whereNull('read_at')->whereDate('created_at', $this->date);
        })
        ->get();
    }

    public function handle()
    {
        Log::info('Processing notification summary job for ' . $this->date);

        $this->users->each(function ($user) {
            Log::info('Sending summary to employee with ID ' . $user->employee_id);

            $notifications = $user
                ->unreadNotifications()
                ->whereDate('created_at', $this->date)
                ->reorder('created_at')
                ->get();

            $user->notify(new NotificationSummaryNotification($this->date, $notifications));
        });

        Log::info('Finished processing notification summary job for ' . $this->date);
    }
}
