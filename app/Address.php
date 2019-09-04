<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'street_number', 'postcode', 'city',
    ];

    public function companies()
    {
        return $this->morphedByMany('App\Company', 'addressable');
    }
}
