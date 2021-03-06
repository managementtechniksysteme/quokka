<?php

namespace App\Models;

use App\Traits\FiltersResults;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use FiltersResults;
    use HasFactory;
    use OrdersResults;

    protected $appends = [
        'full_name',
    ];

    protected $fillable = [
        'name', 'name_2', 'phone', 'fax', 'email', 'website', 'comment',
    ];

    protected $filterFields = [
        'name', 'name_2',
    ];

    protected $filterKeys = [];

    protected $orderKeys = [
        'default' => ['name'],
        'name-asc' => ['name'],
        'name-desc' => [['name', 'desc']],
    ];

    public function address()
    {
        return $this->morphToMany(Address::class, 'addressable')->wherePivot('address_type', 'company');
    }

    public function operatorAddress()
    {
        return $this->morphToMany(Address::class, 'addressable')->wherePivot('address_type', 'operator');
    }

    public function people()
    {
        return $this->hasMany(Person::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name.($this->name_2 ? (' '.$this->name_2) : '');
    }
}
