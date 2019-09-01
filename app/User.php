<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'otp_secret', 'employee_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function getPersonAttribute()
    {
        return $this->employee->person;
    }

    public function routeNotificationForMail()
    {
        return $this->person->email;
    }

    public function getEmailForPasswordReset()
    {
        return $this->person->email;
    }
}
