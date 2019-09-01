<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Person extends Model
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'gender', 'email',
    ];

    public function employee()
    {
        return $this->hasOne('App\Employee');
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
