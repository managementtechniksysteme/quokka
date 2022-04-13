<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationSettings extends Model
{
    protected $casts = [
        'holiday_yearly_allowance' => 'int',
        'task_due_soon_days' => 'int',
    ];

    protected $fillable = [
        'company_id',
        'holiday_service_id',
        'holiday_yearly_allowance',
        'signature_notify_user_id',
        'task_due_soon_days',
    ];

    public static function get()
    {
        return ApplicationSettings::all()->first();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function holidayService()
    {
        return $this->belongsTo(WageService::class, 'holiday_service_id');
    }

    public function signatureNotifyUser()
    {
        return $this->belongsTo(User::class, 'signature_notify_user_id', 'employee_id');
    }
}
