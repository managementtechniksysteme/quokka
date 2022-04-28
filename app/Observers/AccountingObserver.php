<?php

namespace App\Observers;

use App\Models\Accounting;
use App\Models\ApplicationSettings;

class AccountingObserver
{
    public function created(Accounting $accounting)
    {
        if(!ApplicationSettings::get()->holidayService()->exists()) {
            return;
        }

        if($this->isHolidayService($accounting)) {
            $this->removeHolidayFromEmployee($accounting, $accounting->amount);
        }
    }

    public function updated(Accounting $accounting)
    {
        if(!ApplicationSettings::get()->holidayService()->exists()) {
            return;
        }

        if($this->stayedHolidayService($accounting)) {
            $this->addHolidayToEmployee($accounting, $accounting->getOriginal('amount') - $accounting->amount);
        }
        elseif ($this->changedFromHolidayService($accounting)) {
            $this->addHolidayToEmployee($accounting, $accounting->amount);
        }
        elseif ($this->changedToHolidayService($accounting)) {
            $this->removeHolidayFromEmployee($accounting, $accounting->amount);
        }
    }

    public function deleted(Accounting $accounting)
    {
        if(!ApplicationSettings::get()->holidayService()->exists()) {
            return;
        }

        if($this->wasHolidayService($accounting)) {
            $this->addHolidayToEmployee($accounting, $accounting->amount);
        }
    }

    private function addHolidayToEmployee(Accounting $accounting, float $amount)
    {
        $accounting->employee->update(['holidays' => $accounting->employee->holidays + $amount]);
    }

    private function removeHolidayFromEmployee(Accounting $accounting, float $amount)
    {
        $accounting->employee->update(['holidays' => $accounting->employee->holidays - $amount]);
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
