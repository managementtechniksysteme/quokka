<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryNoteStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'written_on' => 'required|date',
            'title' => 'required|unique:delivery_notes',
            'comment' => 'nullable',
            'document' => 'required|mimes:pdf',
            'project_id' => 'required|exists:projects,id',
        ];
    }

}
