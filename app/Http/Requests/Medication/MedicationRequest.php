<?php

namespace App\Http\Requests\Medication;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MedicationRequest extends FormRequest
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
            'medications' => ['required', 'array'],
            'medications.*.medication_name' => ['required', 'string', 'max:255'],
            'medications.*.administered_at' => ['sometimes'],
            'medications.*.dosage' => ['nullable', 'string', 'max:255'],
            'medications.*.frequency' => ['nullable', 'string', 'max:255'],
            'medications.*.administering_veterinarian' => ['nullable', 'string', 'max:255'],
            'medications.*.notes' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'medications.required' => 'The histories field is required.',
            'medications.*.medication_name.required' => 'The medication name is required.',
            'medications.*.administered_at.required' => 'The administering date is required.',
            'medications.*.administered_at.date' => 'The administering date must be a valid date.',
        ];
    }
}