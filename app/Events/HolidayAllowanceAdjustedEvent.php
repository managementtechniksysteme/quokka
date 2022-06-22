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
    public bool $manualAdjustment;
    public User $user;
    public bool $notifySelf;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Employee $employee, float $oldHolidayAllowance, float $currentHolidayAllowance, User $user, bool $notifySelf, bool $manualAdjustment = false)
    {
        $this->employee = $employee;
        $this->oldHolidayAllowance = $oldHolidayAllowance;
        $this->currentHolidayAllowance = $currentHolidayAllowance;
        $this->manualAdjustment = $manualAdjustment;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }
}
