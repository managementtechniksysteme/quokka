<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\ApplicationVersionUpdateNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendApplicationVersionUpdateNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a notification to all users about a new application version.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Sending application version notification to all users!');

        Notification::send(User::all(), new ApplicationVersionUpdateNotification());
    }
}
