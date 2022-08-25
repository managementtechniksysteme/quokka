<?php

namespace App\Jobs;

use App\Events\HolidayAllowanceAdjustedEvent;
use App\Models\ApplicationSettings;
use App\Models\Employee;
use App\Notifications\HolidayAllowanceAdjustmentNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdjustHolidayAllowanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Carbon $currentDate;
    public $yearlyHolidayAllowance;
    public $employees;

    public function __construct()
    {
        $this->yearlyHolidayAllowance = ApplicationSettings::get()->holiday_yearly_allowance;
        $this->currentDate = Carbon::now();
        $this->employees = Employee::whereMonth('entered_on', $this->currentDate)
            ->whereDay('entered_on', $this->currentDate)->get();
    }

    public function handle()
    {
        if($this->yearlyHolidayAllowance === null) {
            return;
        }

        Log::info('Processing holiday allowance adjustements for ' . $this->currentDate);

        $this->employees->each(function ($employee) {
            try {
                Log::info('Increasing holiday allowance for employee ID ' .
                    $employee->person_id . ' by ' . $this->yearlyHolidayAllowance);

                $oldHolidayAllowance = $employee->holidays;

                $employee->update(['holidays' => $employee->holidays + $this->yearlyHolidayAllowance]);

                event(new HolidayAllowanceAdjustedEvent($employee, $oldHolidayAllowance, $employee->holidays, null,
                    null, false));
            } catch (\Exception $e) {
                Log::error('Failed to increase holiday allowance adjustment for employee ID ' . $employee->person_id);
                Log::error($e);
            }
        });

        Log::info('Finished processing holiday allowance adjustements for ' . $this->currentDate);
    }
}
