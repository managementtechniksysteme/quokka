<?php

namespace App\Models;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class InterimInvoice extends Model
{
    use OrdersResults;

    protected $casts = [
        'billed_on' => 'date',
        'amount' => 'double',
    ];

    protected $fillable = [
        'title', 'billed_on', 'amount', 'comment', 'project_id',
    ];

    protected $orderKeys = [
        'default' => ['billed_on'],
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
