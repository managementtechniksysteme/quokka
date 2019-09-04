<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $casts = [
        'person_id' => 'int',
        'entered_on' => 'date',
        'left_on' => 'date',
        'holidays' => 'int',
    ];

    protected $fillable = [
        'person_id', 'entered_on', 'left_on', 'holidays',
    ];

    protected $primaryKey = 'person_id';
    public $incrementing = false;

    public function person()
    {
        return $this->belongsTo('App\Person');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'employee_id');
    }
}
