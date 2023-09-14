<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnalyzeXMLRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'archivo' => ['required', 'file', 'mimes:xml', 'between:1,9000'],
        ];
    }

    public function messages()
    {
        return [
            'archivo.required' => 'Adjuntar un archivo XML válido',
            'archivo.mimes' => 'Archivo debe ser de formato: xml',
            'archivo.size' => 'El tamaño de Archivo debe ser menor de :size kilobytes',
        ];
    }

    public function attributes()
    {
        return [
            'archivo' => 'Archivo',
        ];
    }
}
