<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mp3,pdf|max:20480',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'File upload is required.',
            'file.file' => 'The upload must be a file.',
            'file.mimes' => 'The file type must be one of the following: jpg, jpeg, png, gif, mp4, mp3, pdf.',
            'file.max' => 'The file may not be greater than 20MB.',
        ];
    }
}
