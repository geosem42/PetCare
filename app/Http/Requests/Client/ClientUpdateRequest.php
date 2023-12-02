<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClientUpdateRequest extends FormRequest
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
    public function rules(): array
    {
        return [
			'name' => ['required', 'string', 'max:255'],
			'email' => 'required|email|unique:users,email,' . $this->route('id'),
			'phone_number' => ['nullable', 'integer'],
			'address' => ['nullable', 'string'],
			'password' => 'nullable|string|min:8',
			'notes' => ['nullable', 'string', 'max:60000'],
		];
    }
}
