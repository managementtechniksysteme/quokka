<?php

namespace App\Notifications;

use App\Models\ApplicationSettings;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class HolidayAllowanceAdjustmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $holidayAllowanceDifference;
    public int $currentHolidayAllowance;
    public string $holidayServiceUnit;
    public string $directionString;

    public function __construct(int $oldHolidayAllowance, int $currentHolidayAllowance)
    {
        $holidayAllowanceDifference = $currentHolidayAllowance-$oldHolidayAllowance;

        $this->currentHolidayAllowance = $currentHolidayAllowance;
        $this->holidayAllowanceDifference = abs($holidayAllowanceDifference);
        $this->directionString = $holidayAllowanceDifference > 0 ? 'erhöht' : 'verringert';
        $this->holidayServiceUnit = ApplicationSettings::get()->holidayService->unit;
    }

    public function via($notifiable): array
    {
        return [
            MailChannel::class,
            WebPushChannel::class,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Anpassung deines verfügbaren Urlaubes')
            ->markdown('emails.notification_holiday_allowance_adjustment', [
                'currentHolidayAllowance' => $this->currentHolidayAllowance,
                'holidayAllowanceDifference' => $this->holidayAllowanceDifference,
                'directionString' => $this->directionString,
                'holidayServiceUnit' => $this->holidayServiceUnit
            ]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Jährliche Urlaubsanpassung')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Dein verfügbarer Urlaub wurde um ' .
                $this->holidayAllowanceDifference . ' ' . $this->holidayServiceUnit . ' ' . $this->directionString . '.' .
                'Dein aktueller Stand beträgt ' . $this->currentHolidayAllowance . ' ' . $this->holidayServiceUnit . '.')
            ->vibrate($this->vibrationDuration);
    }
}
