<?php

namespace App\Models;

use App\Traits\HasDownloadRequest;
use App\Traits\HasSignatureRequest;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class ServiceReport extends Model implements HasMedia
{
    use HasDownloadRequest;
    use HasSignatureRequest;
    use OrdersResults;

    protected $casts = [
        'number' => 'int',
    ];

    protected $fillable = [
        'number', 'status', 'comment', 'project_id', 'employee_id',
    ];

    protected $orderKeys = [
        'default' => ['number'],
        'number-asc' => ['number'],
        'number-desc' => [['number', 'desc']],
        'status-asc' => ['raw' => 'field(status, "new", "signed", "finished"), number'],
        'status-desc' => ['raw' => 'field(status, "finished", "signed", "new"), number'],
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signature')->singleFile()->useDisk('local');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function services()
    {
        return $this->hasMany(ServiceReportService::class);
    }

    public function isNew()
    {
        return $this->status === 'new';
    }

    public function isSigned()
    {
        return $this->status === 'signed';
    }

    public function isFinished()
    {
        return $this->status === 'finished';
    }
}
