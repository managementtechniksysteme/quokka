<?php

namespace App\Observers;

use App\Models\Accounting;
use App\Models\ApplicationSettings;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountingObserver
{
    public function created(Accounting $accounting)
    {
        if(!ApplicationSettings::get()->holidayService()->exists()) {
            return;
        }

        if($this->isHolidayService($accounting)) {
            DB::transaction(function () use ($accounting) {
                $accounting->employee()->lockForUpdate()->get();
                $this->removeHolidayFromEmployee($accounting->employee, $accounting->amount);
            });
        }
    }

    public function updated(Accounting $accounting)
    {
        if(!ApplicationSettings::get()->holidayService()->exists()) {
            return;
        }

        if($this->stayedHolidayService($accounting)) {
            DB::transaction(function () use ($accounting) {
                $accounting->employee()->lockForUpdate()->get();
                $this->addHolidayToEmployee($accounting->employee, $accounting->getOriginal('amount') - $accounting->amount);
            });
        }
        elseif ($this->changedFromHolidayService($accounting)) {
            DB::transaction(function () use ($accounting) {
                $accounting->employee()->lockForUpdate()->get();
                $this->addHolidayToEmployee($accounting->employee, $accounting->getOriginal('amount'));
            });
        }
        elseif ($this->changedToHolidayService($accounting)) {
            DB::transaction(function () use ($accounting) {
                $accounting->employee()->lockForUpdate()->get();
                $this->removeHolidayFromEmployee($accounting->employee, $accounting->amount);
            });
        }
    }

    public function deleted(Accounting $accounting)
    {
        if(!ApplicationSettings::get()->holidayService()->exists()) {
            return;
        }

        if($this->wasHolidayService($accounting)) {
            DB::transaction(function () use ($accounting) {
                $accounting->employee()->lockForUpdate()->get();
                $this->addHolidayToEmployee($accounting->employee, $accounting->amount);
            });
        }
    }

    private function addHolidayToEmployee(Employee $employee, float $amount)
    {
        $employee->increment('holidays', $amount);
    }

    private function removeHolidayFromEmployee(Employee $employee, float $amount)
    {
        $employee->decrement('holidays', $amount);
    }

    private function wasHolidayService(Accounting $accounting)
    {
        return $accounting->getOriginal('service_id') === ApplicationSettings::get()->holidayService->id;
    }

    private function isHolidayService(Accounting $accounting)
    {
        return $accounting->service_id === ApplicationSettings::get()->holidayService->id;
    }

    private function changedFromHolidayService(Accounting $accounting)
    {
        return $this->wasHolidayService($accounting) && !$this->isHolidayService($accounting);
    }

    private function changedToHolidayService(Accounting $accounting)
    {
        return !$this->wasHolidayService($accounting) && $this->isHolidayService($accounting);
    }

    private function stayedHolidayService(Accounting $accounting)
    {
        return $this->wasHolidayService($accounting) && $this->isHolidayService($accounting);
    }
}
