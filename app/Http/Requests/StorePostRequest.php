<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => 'required|string|min:2',
            'content' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'The title  is mandatory.',
            'title.min' => 'The title must be at least :min characters long.',
            'title.string' => 'The title must be a string.',
            'content.required' => 'The content is mandatory.',
            'content.string' => 'The content must be a string.',


        ];
    }
}
