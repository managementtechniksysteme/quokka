<?php

namespace App;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Person extends Model
{
    use Notifiable;
    use OrdersResults;

    protected $appends = [
        'name',
    ];

    protected $fillable = [
        'first_name', 'last_name', 'title_prefix', 'title_suffix', 'gender', 'department', 'role', 'phone_company',
        'phone_mobile', 'phone_private', 'fax', 'email', 'website', 'comment', 'company_id',
    ];

    protected $orderKeys = [
        'default' => ['first_name', 'last_name'],
        'first-name-asc' => ['first_name', 'last_name'],
        'first-name-desc' => [['first_name', 'desc'], ['last_name', 'desc']],
        'last-name-asc' => ['last_name', 'first_name'],
        'last-name-desc' => [['last_name', 'desc'], ['first_name', 'desc']],
    ];

    public function address()
    {
        return $this->morphToMany(Address::class, 'addressable');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function memosPresentIn()
    {
        return $this->morphedByMany(Memo::class, 'personable')->wherePivot('person_type', 'present');
    }

    public function memosNotifiedBy()
    {
        return $this->morphedByMany(Memo::class, 'personable')->wherePivot('person_type', 'notified');
    }

    public function memosReceived()
    {
        return $this->hasMany(Memo::class);
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
