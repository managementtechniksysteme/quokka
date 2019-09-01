<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'entered_on', 'left_on', 'person_id', 'holidays',
    ];

    protected $casts = [
        'entered_on' => 'date',
        'left_on' => 'date',
    ];

    public function person()
    {
        return $this->belongsTo('App\Person');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }
}
