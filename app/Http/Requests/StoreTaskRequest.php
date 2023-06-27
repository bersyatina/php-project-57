<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:tasks,name|max:255',
            'status_id' => 'required',
            'assigned_to_id' => 'nullable',
            'description' => 'nullable|max:255',
            'labels' => 'nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => __('errors.task.name_required'),
            'name.max' => __('errors.task.name_max'),
            'name.unique' => __('errors.task.name_unique'),
            'status_id.required' => __('errors.task.status_id_required'),
            'assigned_to_id.required' => __('errors.task.assigned_to_id_required'),
            'description.max' => __('errors.task.description_max'),
        ];
    }
}
