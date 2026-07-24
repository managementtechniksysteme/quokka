<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShareTargetRequest extends FormRequest
{
    /**
     * The manifest declares the field name as plain "photos" (no []), and
     * that's the literal multipart field name the browser sends -- PHP only
     * auto-collects an array when the field name itself has brackets, so a
     * single shared photo arrives as one plain UploadedFile, not an array.
     * Normalize before validating rather than assuming it's array-shaped.
     */
    protected function prepareForValidation()
    {
        $photos = $this->file('photos');

        if ($photos && ! is_array($photos)) {
            $this->files->set('photos', [$photos]);
            // file()/allFiles() cache their result in $convertedFiles the
            // moment they're first called (the line above) -- without this,
            // every later read (the validator, then the controller) would
            // keep seeing the stale, pre-normalization single file.
            $this->convertedFiles = null;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photos' => 'required|array|min:1',
            'photos.*' => 'image',
        ];
    }
}
