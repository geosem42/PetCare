<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GalleryRequest extends FormRequest
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
            'files' => 'required|array',
            'files.*' => 'mimes:jpg,png,jpeg|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'files.required' => 'Select an image to upload.',
            'files.*.mimes' => 'The uploaded file must be a file of type: jpg, png, jpeg.',
            'files.*.max' => 'The uploaded file must not be greater than 1048 kilobytes.',
        ];
    }
}