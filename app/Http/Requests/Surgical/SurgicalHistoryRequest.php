<?php

namespace App\Http\Requests\Surgical;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SurgicalHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'surgeries' => ['required', 'array'],
            'surgeries.*.procedure_name' => ['required', 'string'],
            'surgeries.*.date' => ['required', 'date'],
            'surgeries.*.surgeon' => ['nullable', 'string'],
            'surgeries.*.notes' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'surgeries.required' => 'The surgeries field is required.',
            'surgeries.*.procedure_name.required' => 'The procedure name is required.',
            'surgeries.*.date.required' => 'The date is required.',
            'surgeries.*.date.date' => 'The date must be a valid date.',
        ];
    }
}