<?php

namespace App\Jobs;

use App\Models\ApplicationSettings;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;

class PruneSentEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Carbon $date;
    public $activities;

    public function __construct()
    {
        $this->date = Carbon::today()->subMonth();
        $this->activities = Activity::forEvent('email')
            ->whereDate('created_at', '<', $this->date)
            ->get();
    }

    public function handle()
    {
        if(!ApplicationSettings::get()->prune_sent_emails) {
            return;
        }

        Log::info('Pruning sent emails older than ' . $this->date);

        if($this->activities->count()) {
            Log::info('Pruning ' . $this->activities->count() . ' sent emails.');

            $this->activities->delete();
        }

        Log::info('Finished pruning sent emails older than ' . $this->date);
    }
}
