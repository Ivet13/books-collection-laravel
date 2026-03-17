<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'La imagen es obligatoria',
            'image.image' => 'La imagen debe ser un archivo de imagen',
            'image.max' => 'La imagen debe tener un tamaño máximo de 8MB',
            'image.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg, gif, svg o webp',
        ];
    }
}
