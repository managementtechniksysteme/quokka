<?php

namespace App\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasAttachmentsAndSignatureRequests
{
    use HasAttachments, HasSignatureRequest {
        HasAttachments::addAllMediaFromRequest insteadof HasSignatureRequest;
        HasAttachments::addFromMediaLibraryRequest insteadof HasSignatureRequest;
        HasAttachments::addMedia insteadof HasSignatureRequest;
        HasAttachments::addMediaCollection insteadof HasSignatureRequest;
        HasAttachments::addMediaConversion insteadof HasSignatureRequest;
        HasAttachments::addMediaFromBase64 insteadof HasSignatureRequest;
        HasAttachments::addMediaFromDisk insteadof HasSignatureRequest;
        HasAttachments::addMediaFromRequest insteadof HasSignatureRequest;
        HasAttachments::addMediaFromString insteadof HasSignatureRequest;
        HasAttachments::addMediaFromUrl insteadof HasSignatureRequest;
        HasAttachments::addMultipleMediaFromRequest insteadof HasSignatureRequest;
        HasAttachments::bootInteractsWithMedia insteadof HasSignatureRequest;
        HasAttachments::clearMediaCollection insteadof HasSignatureRequest;
        HasAttachments::clearMediaCollectionExcept insteadof HasSignatureRequest;
        HasAttachments::copyMedia insteadof HasSignatureRequest;
        HasAttachments::deleteMedia insteadof HasSignatureRequest;
        HasAttachments::deletePreservingMedia insteadof HasSignatureRequest;
        HasAttachments::getFallbackMediaPath insteadof HasSignatureRequest;
        HasAttachments::getFallbackMediaUrl insteadof HasSignatureRequest;
        HasAttachments::getFirstMedia insteadof HasSignatureRequest;
        HasAttachments::getFirstMediaPath insteadof HasSignatureRequest;
        HasAttachments::getFirstMediaUrl insteadof HasSignatureRequest;
        HasAttachments::getFirstTemporaryUrl insteadof HasSignatureRequest;
        HasAttachments::getMedia insteadof HasSignatureRequest;
        HasAttachments::getMediaCollection insteadof HasSignatureRequest;
        HasAttachments::getRegisteredMediaCollections insteadof HasSignatureRequest;
        HasAttachments::guardAgainstInvalidMimeType insteadof HasSignatureRequest;
        HasAttachments::hasMedia insteadof HasSignatureRequest;
        HasAttachments::loadMedia insteadof HasSignatureRequest;
        HasAttachments::media insteadof HasSignatureRequest;
        HasAttachments::mediaIsPreloaded insteadof HasSignatureRequest;
        HasAttachments::prepareToAttachMedia insteadof HasSignatureRequest;
        HasAttachments::processUnattachedMedia insteadof HasSignatureRequest;
        HasAttachments::registerAllMediaConversions insteadof HasSignatureRequest;
        HasAttachments::registerMediaCollections insteadof HasSignatureRequest;
        HasAttachments::registerMediaConversions insteadof HasSignatureRequest;
        HasAttachments::removeMediaItemsNotPresentInArray insteadof HasSignatureRequest;
        HasAttachments::shouldDeletePreservingMedia insteadof HasSignatureRequest;
        HasAttachments::syncFromMediaLibraryRequest insteadof HasSignatureRequest;
        HasAttachments::updateMedia insteadof HasSignatureRequest;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')->useDisk('local');
        $this->addMediaCollection('signature')->singleFile()->useDisk('local');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')->width(50)->height(50)->performOnCollections('attachments');
    }
}
