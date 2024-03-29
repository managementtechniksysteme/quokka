<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use HasPushSubscriptions;
    use HasRoles;
    use InteractsWithMedia;
    use Notifiable;
    use SoftDeletes;

    protected $casts = [
        'employee_id' => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id', 'username', 'password', 'otp_secret',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function signature()
    {
        return $this->getFirstMedia('signature');
    }

    public function settings()
    {
        return $this->hasOne(UserSettings::class, 'user_id');
    }

    public function notificationsViaEmail()
    {
        return $this->morphToMany(NotificationType::class, 'notifiable', null, 'notifiable_id', 'notification_type_id')->wherePivot('notification_target_type', 'email')->withTimestamps();
    }

    public function notificationsViaWebPush()
    {
        return $this->morphToMany(NotificationType::class, 'notifiable', null, 'notifiable_id', 'notification_type_id')->wherePivot('notification_target_type', 'webpush')->withTimestamps();
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signature')->singleFile()->useDisk('local');
    }

    public function addSignature(string $signature)
    {
        $this->addMediaFromBase64($signature)->usingFileName('signature.png')->toMediaCollection('signature');
    }

    public function deleteSignature()
    {
        $signature = $this->signature();

        if ($signature) {
            $signature->delete();
        }
    }
}
