<?php

namespace App;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use OrdersResults;

    protected $appends = [
        'address_line',
    ];

    protected $fillable = [
        'street_number', 'postcode', 'city',
    ];

    protected $orderKeys = [
        'default' => ['street_number', 'postcode', 'city'],
        'street-number-asc' => ['street_number', 'postcode', 'city'],
        'street-number-desc' => [['street_number', 'desc'], ['postcode', 'desc'], ['city', 'desc']],
        'postcode-asc' => ['postcode', 'city', 'street_number'],
        'poscode-desc' => [['postcode', 'desc'], ['city', 'desc'], ['street_number', 'desc']],
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
