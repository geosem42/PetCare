<?php

namespace App\Http\Requests\Vaccination;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VaccinationRequest extends FormRequest
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
            'vaccinations' => ['required', 'array'],
            'vaccinations.*.vaccine_name' => ['required', 'string', 'max:255'],
            'vaccinations.*.administered_at' => ['required'],
            'vaccinations.*.batch_number' => ['nullable', 'string', 'max:255'],
            'vaccinations.*.administering_veterinarian' => ['nullable', 'string', 'max:255'],
            'vaccinations.*.notes' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'vaccinations.required' => 'The histories field is required.',
            'vaccinations.*.vaccine_name.required' => 'The vaccine name is required.',
            'vaccinations.*.administered_at.required' => 'The administering date is required.',
            'vaccinations.*.administered_at.date' => 'The administering date must be a valid date.',
        ];
    }
}