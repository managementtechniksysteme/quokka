<?php

namespace App\Models;

use App\Traits\FiltersResults;
use App\Traits\HasAttachmentsAndSignatureRequests;
use App\Traits\HasDownloadRequest;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class ServiceReport extends Model implements HasMedia
{
    use FiltersResults;
    use HasAttachmentsAndSignatureRequests;
    use HasDownloadRequest;
    use OrdersResults;

    protected $casts = [
        'number' => 'int',
    ];

    protected $fillable = [
        'number', 'status', 'comment', 'project_id', 'employee_id',
    ];

    protected $filterFields = [
        'number', 'comment',
    ];

    protected $filterKeys = [
        'ist:neu' => ['status', 'new'],
        'ist:unterschrieben' => ['status', 'signed'],
        'ist:u' => ['status', 'signed'],
        'ist:erledigt' => ['status', 'finished'],
        'nummer:(\d)' => ['number', '{value}'],
        'n:(\d)' => ['number', '{value}'],
        'projekt:(.*)' => ['project.name', '{value}'],
        'p:(.*)' => ['project.name', '{value}'],
        'techniker:(.*)' => ['employee.user.username', '{value}'],
        't:(.*)' => ['employee.user.username', '{value}'],
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
        $this->addMediaCollection('attachments')->useDisk('local');
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
