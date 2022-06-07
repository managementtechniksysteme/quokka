<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasAttachments
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')->useDisk('local');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')->width(50)->height(50);
    }

    public function attachments()
    {
        return $this->getMedia('attachments');
    }

    public function attachmentsWithUrl()
    {
        $attachments = collect();

        foreach ($this->attachments() as $attachment) {
            if ($attachment->hasGeneratedConversion('thumbnail')) {
                $attachment->setAttribute('url', $attachment->getUrl('thumbnail'));
            } else {
                $attachment->setAttribute('url', null);
            }

            $attachments->push($attachment);
        }

        return $attachments;
    }

    public function addAttachments($attachments)
    {
        if (! $attachments) {
            return;
        }

        foreach ($attachments as $attachment) {
            $this->addMedia($attachment)->toMediaCollection('attachments');
        }
    }

    public function deleteAttachments($attachments = null)
    {
        $attachmentsToDelete = $attachments ? $this->attachments()->find($attachments) : $this->attachments();

        if ($attachmentsToDelete) {
            $attachmentsToDelete->each->forceDelete();
        }
    }

    public function deleteAllAttachments() {
        $this->deleteAttachments($this->attachments());
    }
}
