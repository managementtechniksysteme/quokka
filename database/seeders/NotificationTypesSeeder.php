<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use App\Models\User;
use App\Notifications\AdditionsReportInvolvedNotification;
use App\Notifications\AdditionsReportMentionNotification;
use App\Notifications\AdditionsReportSignedNotification;
use App\Notifications\ApplicationVersionUpdateNotification;
use App\Notifications\CommentInvolvedNotification;
use App\Notifications\CommentMentionNotification;
use App\Notifications\ConstructionReportInvolvedNotification;
use App\Notifications\ConstructionReportMentionNotification;
use App\Notifications\ConstructionReportSignedNotification;
use App\Notifications\DeliveryNoteSignedNotification;
use App\Notifications\FlowMeterInspectionReportMentionNotification;
use App\Notifications\FlowMeterInspectionReportSignedNotification;
use App\Notifications\HolidayAllowanceAdjustmentNotification;
use App\Notifications\InspectionReportMentionNotification;
use App\Notifications\InspectionReportSignedNotification;
use App\Notifications\MemoInvolvedNotification;
use App\Notifications\MemoMentionNotification;
use App\Notifications\ServiceReportMentionNotification;
use App\Notifications\ServiceReportSignedNotification;
use App\Notifications\TaskInvolvedNotification;
use App\Notifications\TaskMentionNotification;
use Illuminate\Database\Seeder;

class NotificationTypesSeeder extends Seeder
{
    public function run()
    {
        $notifications = [
            AdditionsReportInvolvedNotification::class,
            AdditionsReportMentionNotification::class,
            AdditionsReportSignedNotification::class,
            ApplicationVersionUpdateNotification::class,
            CommentInvolvedNotification::class,
            CommentMentionNotification::class,
            ConstructionReportInvolvedNotification::class,
            ConstructionReportMentionNotification::class,
            ConstructionReportSignedNotification::class,
            DeliveryNoteSignedNotification::class,
            FlowMeterInspectionReportMentionNotification::class,
            FlowMeterInspectionReportSignedNotification::class,
            HolidayAllowanceAdjustmentNotification::class,
            InspectionReportMentionNotification::class,
            InspectionReportSignedNotification::class,
            MemoInvolvedNotification::class,
            MemoMentionNotification::class,
            ServiceReportMentionNotification::class,
            ServiceReportSignedNotification::class,
            TaskInvolvedNotification::class,
            TaskMentionNotification::class,
        ];

        foreach ($notifications as $notification) {
            $notificationType = NotificationType::firstOrCreate([
               'type' => $notification,
            ]);

            if ($notificationType->wasRecentlyCreated) {
                User::each(function ($user) use ($notificationType) {
                    $user->notificationsViaEmail()->attach($notificationType, ['notification_target_type' => 'email']);
                    $user->notificationsViaWebPush()->attach($notificationType, ['notification_target_type' => 'webpush']);
                });
            }
        }
    }
}
