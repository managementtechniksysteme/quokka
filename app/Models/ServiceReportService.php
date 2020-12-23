<?php

namespace App\Models;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class ServiceReportService extends Model
{
    use OrdersResults;

    protected $casts = [
        'provided_on' => 'date',
        'hours' => 'double',
        'allowances' => 'double',
        'kilometres' => 'double',
    ];

    protected $fillable = [
        'provided_on', 'hours', 'allowances', 'kilometres', 'service_report_id'
    ];

    protected $orderKeys = [
        'default' => ['provided_on'],
    ];

    public function serviceReport()
    {
        return $this->belongsTo(ServiceReport::class);
    }
}
