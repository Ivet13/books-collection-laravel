<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }


    protected function prepareForValidation()
    {
        if ($this->has('images') && is_string($this->images)) {
            $this->merge([
                'images' => json_decode($this->images, true),
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'images' => 'nullable|array',
            'images.*.title' => 'nullable|string',
            'images.*.alt' => 'nullable|string',
            'images.*.filename' => 'nullable|string',

        ];
    }
}
