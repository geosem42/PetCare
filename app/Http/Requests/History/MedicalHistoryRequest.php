<?php

namespace App\Http\Requests\History;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'histories' => ['required', 'array'],
            'histories.*.condition' => ['required', 'string'],
            'histories.*.diagnosis_date' => ['sometimes', 'date'],
            'histories.*.treatment' => ['nullable', 'string'],
            'histories.*.notes' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'histories.required' => 'The histories field is required.',
            'histories.*.condition.required' => 'The condition name is required.',
            'histories.*.diagnosis_date.date' => 'The diagnosis date must be a valid date.',
        ];
    }
}