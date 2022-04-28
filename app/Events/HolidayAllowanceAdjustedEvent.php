<?php

namespace App\Events;

use App\Models\Employee;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HolidayAllowanceAdjustedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Employee $employee;
    public float $oldHolidayAllowance;
    public float $currentHolidayAllowance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Employee $employee, float $oldHolidayAllowance, float $currentHolidayAllowance)
    {
        $this->employee = $employee;
        $this->oldHolidayAllowance = $oldHolidayAllowance;
        $this->currentHolidayAllowance = $currentHolidayAllowance;
    }
}
