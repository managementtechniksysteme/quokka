<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasPushSubscriptions;
    use Notifiable;
    use SoftDeletes;

    protected $casts = [
        'employee_id' => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'username', 'password', 'otp_secret',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $primaryKey = 'employee_id';
    public $incrementing = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
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

    public function getUsernameAvatarStringAttribute()
    {
        return strtoupper($this->username);
    }
}
