<?php

namespace App\Http\Controllers;

use App\Models\TemporaryUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UploadController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('role:admin')->only(['']);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function storeImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => File::image()
        ]);

        $temporaryUpload = TemporaryUpload::create();
        $media = $temporaryUpload->addMediaFromRequest('image')->toMediaCollection();

        return $this->handleResponse(['id' => $media->uuid, 'path' => $media->getUrl()]);
    }
}
