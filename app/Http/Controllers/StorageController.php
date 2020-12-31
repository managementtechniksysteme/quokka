<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class StorageController extends Controller
{
    public function getFile(string $filePath)
    {
        if (! Storage::disk('local')->exists($filePath)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $local_path = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR.$filePath;

        return response()->file($local_path);
    }
}
