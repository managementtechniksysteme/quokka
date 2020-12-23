<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadRequest extends Model
{
    protected $casts = [
        'requestable_id' => 'int',
    ];

    protected $fillable = [
        'requestable_id', 'requestable_type', 'token',
    ];

    protected $primaryKey = 'token';
    protected $keyType = 'string';
    public $incrementing = false;

    public function requestable()
    {
        return $this->morphTo();
    }

    public static function fromToken(string $requestableType, string $token)
    {
        return DownloadRequest::whereRequestableType($requestableType)->whereToken($token)->first()->requestable ?? null;
    }
}
