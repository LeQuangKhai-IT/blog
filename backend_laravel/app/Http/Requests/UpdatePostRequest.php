<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'slug' => 'sometimes|required|string|unique:posts,slug,' . $this->route('post'),
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image_url' => 'nullable|url',
            'user_id' => 'sometimes|required|exists:users,id|uuid',
            'category_id' => 'sometimes|required|exists:categories,id|uuid',
            'published' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ];
    }
}
