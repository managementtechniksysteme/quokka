<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserSettingsUpdateInterfaceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'list_pagination_size' => 'required|integer|in:5,10,15,20,25,30',
            'show_finished_items' => 'required|boolean',
            'task_comments_sort_newest_first' => 'required|boolean',
            'accounting_expand_errors' => 'required|boolean',
        ];
    }
}
