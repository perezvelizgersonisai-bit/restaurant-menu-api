<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas "sometimes": en PUT/PATCH solo se validan los campos
     * que realmente vienen en la petición, permitiendo actualizaciones
     * parciales sin exigir de nuevo todos los campos obligatorios.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'category' => ['sometimes', 'required', 'string', 'max:100'],
            'available' => ['sometimes', 'boolean'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del platillo no puede quedar vacío.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'price.min' => 'El precio no puede ser negativo.',
            'image_url.url' => 'La URL de la imagen no es válida.',
        ];
    }
}
