<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $appends = [
        'address_line',
    ];

    protected $fillable = [
        'street_number', 'postcode', 'city',
    ];

    public function companies()
    {
        return $this->morphedByMany(Company::class, 'addressable');
    }

    public function people()
    {
        return $this->morphedByMany(Person::class, 'addressable');
    }

    public function getAddressLineAttribute()
    {
        return "{$this->street_number}, {$this->postcode} {$this->city}";
    }
}
