<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryNoteUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $oldStatus = $this->delivery_note->status;
        $newStatus = $this->input('status');

        $rules = [
            'written_on' => 'required|date',
            'title' => 'required|unique:delivery_notes,'.$this->delivery_note->id,
            'comment' => 'nullable',
            'document' => 'mimes:pdf',
            'project_id' => 'required|exists:projects,id',
        ];

        if($oldStatus === 'signed' && $newStatus === 'new') {
            $rules['document'] = $rules['document'].'|required';
        }

        return $rules;
    }

}
