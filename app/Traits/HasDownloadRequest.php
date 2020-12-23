<?php

namespace App\Traits;

use App\Models\DownloadRequest;
use Spatie\MediaLibrary\InteractsWithMedia;

trait HasDownloadRequest
{
    public function downloadRequest() {
        return $this->morphOne(DownloadRequest::class, 'requestable');
    }

    public function generateDownloadRequest() {
        $downloadRequest = $this->downloadRequest ?? DownloadRequest::make();

        do {
            $downloadRequest->token = \Str::random(64);
        } while(DownloadRequest::find($downloadRequest->token));

        $downloadRequest->requestable()->associate($this);

        $downloadRequest->save();
    }

    public function deleteDownloadRequest() {
        $downloadRequest = $this->downloadRequest;

        if ($downloadRequest) {
            $downloadRequest->delete();
        }
    }
}
