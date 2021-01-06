<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationSettings extends Model
{
    protected $fillable = [
        'company_id',
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

    public function signatureNotifyUser()
    {
        return $this->belongsTo(User::class, 'signature_notify_user_id', 'employee_id');
    }
}
