<?php

namespace App\Events;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HolidayAllowanceAdjustedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Employee $employee;
    public float $oldHolidayAllowance;
    public float $currentHolidayAllowance;
    public User|null $user;
    public bool|null $notifySelf;
    public bool $manualAdjustment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Employee $employee, float $oldHolidayAllowance, float $currentHolidayAllowance, User|null $user, bool|null $notifySelf, bool $manualAdjustment = false)
    {
        $this->employee = $employee;
        $this->oldHolidayAllowance = $oldHolidayAllowance;
        $this->currentHolidayAllowance = $currentHolidayAllowance;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
        $this->manualAdjustment = $manualAdjustment;
    }
}
