<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ApplicationSettings extends Model
{
    const CACHE_NAME = 'application-settings';
    const CACHE_TTL = 24 * 60 * 60; // 1 day

    protected $casts = [
        'holiday_yearly_allowance' => 'int',
        'accounting_min_amount' => 'double',
        'project_material_costs_warning_percentage' => 'int',
        'project_wage_costs_warning_percentage' => 'int',
        'project_overall_costs_warning_percentage' => 'int',
        'project_billed_costs_warning_percentage' => 'int',
        'task_due_soon_days' => 'int',
    ];

    protected $fillable = [
        'company_id',
        'holiday_service_id',
        'holiday_yearly_allowance',
        'currency_unit',
        'allowances_service_id',
        'overtime_50_service_id',
        'overtime_100_service_id',
        'time_balance_service_id',
        'services_hour_unit',
        'accounting_min_amount',
        'project_material_costs_warning_percentage',
        'project_wage_costs_warning_percentage',
        'project_overall_costs_warning_percentage',
        'project_billed_costs_warning_percentage',
        'signature_notify_user_id',
        'task_due_soon_days',
        'prune_read_notifications',
        'prune_sent_emails',
    ];

    public static function get()
    {
        return Cache::remember(static::CACHE_NAME, static::CACHE_TTL, function () {
            return ApplicationSettings::first();
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function holidayService()
    {
        return $this->belongsTo(WageService::class, 'holiday_service_id');
    }

    public function allowancesService()
    {
        return $this->belongsTo(WageService::class, 'allowances_service_id');
    }

    public function overtime50Service()
    {
        return $this->belongsTo(WageService::class, 'overtime_50_service_id');
    }

    public function overtime100Service()
    {
        return $this->belongsTo(WageService::class, 'overtime_100_service_id');
    }

    public function timeBalanceService()
    {
        return $this->belongsTo(WageService::class, 'time_balance_service_id');
    }

    public function signatureNotifyUser()
    {
        return $this->belongsTo(User::class, 'signature_notify_user_id', 'employee_id');
    }

    public static function refreshCache()
    {
        Cache::forget(static::CACHE_NAME);
        Cache::put(static::CACHE_NAME, ApplicationSettings::first(), static::CACHE_TTL);
    }
}
