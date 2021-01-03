<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationSettings extends Model
{
    protected $fillable = [
        'company_id',
    ];

    public static function get()
    {
        return ApplicationSettings::all()->first();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
