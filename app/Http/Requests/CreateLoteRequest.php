<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateLoteRequest extends FormRequest
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
            'num_lote' => ['required', 'min:9', 'max:10'],
            'comment' => ['max:190'],
        ];
    }

    public function messages()
    {
        return [
            'num_lote.required' => 'Número de lote es un campo obligatorio',
            'num_lote.max' => 'Número de lote debe tener menos de :max caracteres',
            'num_lote.min' => 'Número de lote debe tener al menos :min caracteres',
            'comment.max' => 'Comentario debe tener menos de :max caracteres',
        ];
    }

    public function attributes()
    {
        return [
            'num_lote' => 'Número de Lote'
        ];
    }
}
