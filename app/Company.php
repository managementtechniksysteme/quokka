<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'name_2', 'phone', 'fax', 'email', 'website', 'comment',
    ];

    public function address()
    {
        return $this->morphToMany('App\Address', 'addressable');
    }

    public function getNameAttribute()
    {
        return "{$this->name} {$this->name_2}";
    }
}
