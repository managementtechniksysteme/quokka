<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSettingsUpdateInterfaceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'list_pagination_size' => 'required|integer|in:5,10,15,20,25,30',
            'latest_changes_quantity' => 'sometimes|integer|in:5,10,15,20,25,30,40,50',
            'show_finished_items' => 'required|boolean',
            'show_only_own_reports' => 'required|boolean',
            'show_cost_estimates' => 'sometimes|boolean|nullable',
            'task_comments_sort_newest_first' => 'required|boolean',
            'accounting_expand_errors' => 'required|boolean',
            'accounting_filter_default_days' => 'nullable|integer|min:1',
        ];
    }
}
