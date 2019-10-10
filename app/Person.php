<?php

namespace App;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Person extends Model
{
    use Notifiable;
    use OrdersResults;

    protected $fillable = [
        'first_name', 'last_name', 'gender', 'email',
    ];

    protected $orderKeys = [
        'first-name-asc' => ['first_name', 'last_name'],
        'first-name-desc' => [['first_name', 'desc'], ['last_name', 'desc']],
        'last-name-asc' => ['last_name', 'first_name'],
        'last-name-desc' => [['last_name', 'desc'], ['first_name', 'desc']],
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
