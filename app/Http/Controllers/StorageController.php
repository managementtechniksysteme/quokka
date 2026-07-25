<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class StorageController extends Controller
{
    public function getFile(string $filePath)
    {
        if (! Storage::disk('local')->exists($filePath)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $media = Media::find(explode('/', $filePath)[0]);

        abort_if(! $media, Response::HTTP_NOT_FOUND);

        $this->authorize('view', $media->model);

        $local_path = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR.$filePath;

        return response()->file($local_path);
    }
}
