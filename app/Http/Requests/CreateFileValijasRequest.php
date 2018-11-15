<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFileValijasRequest extends FormRequest
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
            'archivo' => ['required', 'file', 'mimes:txt', 'between:1,800'],
            'comment' => ['max:450'],
        ];
    }

    public function messages()
    {
        return [
            'archivo.required' => 'Adjuntar un archivo TXT con los datos de las valijas es obligatorio',
            'archivo.mimes' => 'Archivo debe ser de formato: txt',
            'archivo.size' => 'El tamaño de Archivo debe ser menor de :size kilobytes',
            'comment.max' => 'Comentario debe tener menos de :max caracteres',
        ];
    }

    public function attributes()
    {
        return [
            'archivo' => 'Archivo',
        ];
    }
}
