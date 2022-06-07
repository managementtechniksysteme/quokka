<?php

namespace App\Models;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class ServiceReportService extends Model
{
    use OrdersResults;


    protected $primaryKey = null;
    public $incrementing = false;

    protected $casts = [
        'provided_on' => 'date',
        'hours' => 'double',
        'kilometres' => 'int',
    ];

    protected $fillable = [
        'provided_on', 'hours', 'kilometres', 'service_report_id',
    ];

    protected $orderKeys = [
        'default' => ['provided_on'],
    ];

    protected $touches = [
        'serviceReport'
    ];

    public function serviceReport()
    {
        return $this->belongsTo(ServiceReport::class);
    }
}
