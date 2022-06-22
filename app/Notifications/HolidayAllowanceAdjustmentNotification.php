<?php

namespace App\Notifications;

use App\Models\ApplicationSettings;
use App\Models\Employee;
use App\Traits\TargetsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class HolidayAllowanceAdjustmentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public float $holidayAllowanceDifference;
    public float $currentHolidayAllowance;
    public string $titleMail;
    public string $titleWebpush;
    public string $holidayServiceUnit;
    public string $directionString;
    public bool $manualAdjustment;

    private array $vibrationDuration = ['100'];

    public function __construct(float $oldHolidayAllowance, float $currentHolidayAllowance, bool $manualAdjustment)
    {
        $holidayAllowanceDifference = $currentHolidayAllowance-$oldHolidayAllowance;

        $this->manualAdjustment = $manualAdjustment;

        $this->titleMail =
            $manualAdjustment ? "Anpassung deines verfügbaren Urlaubes" :
                "Automatische Anpassung deines verfügbaren Urlaubes";
        $this->titleWebpush = $manualAdjustment ? "Urlaubsanpassung" : "Automatische Urlaubsanpassung";

        $this->currentHolidayAllowance = $currentHolidayAllowance;
        $this->holidayAllowanceDifference = abs($holidayAllowanceDifference);
        $this->directionString = $holidayAllowanceDifference > 0 ? 'erhöht' : 'verringert';
        $this->holidayServiceUnit = ApplicationSettings::get()->holidayService->unit;
    }

    public function via($notifiable): array
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
            'model' => Employee::class,
            'type' => 'Employee',
            'id' => $notifiable->id,
            'currentHolidayAllowance' => $this->currentHolidayAllowance,
            'holidayAllowanceDifference' => $this->holidayAllowanceDifference,
            'directionString' => $this->directionString,
            'holidayServiceUnit' => $this->holidayServiceUnit,
            'manualAdjustment' => $this->manualAdjustment,
            'route' => route('home'),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->titleMail)
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
            ->title($this->titleWebpush)
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Dein verfügbarer Urlaub wurde um ' .
                $this->holidayAllowanceDifference . ' ' . $this->holidayServiceUnit . ' ' . $this->directionString . '.' .
                ' Dein aktueller Stand beträgt ' . $this->currentHolidayAllowance . ' ' . $this->holidayServiceUnit . '.')
            ->vibrate($this->vibrationDuration);
    }
}
