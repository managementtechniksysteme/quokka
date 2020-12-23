<?php

namespace App\Traits;

use App\Models\SignatureRequest;
use Spatie\MediaLibrary\InteractsWithMedia;

trait HasSignatureRequest
{
    use InteractsWithMedia;

    public function signatureRequest()
    {
        return $this->morphOne(SignatureRequest::class, 'requestable');
    }

    public function signature()
    {
        return $this->getFirstMedia('signature');
    }

    public function generateSignatureRequest()
    {
        $signatureRequest = $this->signatureRequest ?? SignatureRequest::make();

        do {
            $signatureRequest->token = \Str::random(64);
        } while (SignatureRequest::find($signatureRequest->token));

        $signatureRequest->requestable()->associate($this);

        $signatureRequest->save();
    }

    public function deleteSignatureRequest()
    {
        $signatureRequest = $this->signatureRequest;

        if ($signatureRequest) {
            $signatureRequest->delete();
        }
    }

    public function addSignature(string $signature)
    {
        $this->addMediaFromBase64($signature)->usingFileName('signature.png')->toMediaCollection('signature');
    }

    public function deleteSignature()
    {
        $signature = $this->signature();

        if ($signature) {
            $signature->delete();
        }
    }
}
