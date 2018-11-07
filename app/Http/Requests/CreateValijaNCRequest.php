<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateValijaNCRequest extends FormRequest
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
            'archivo' => ['required', 'file', 'mimes:pdf', 'between:100,8000'],
            'num_oficio_ca' => ['required', 'max:32'],
            'fecha_recepcion_ca' => ['required', 'before_or_equal:today'],
            'num_oficio_del' => ['required', 'max:32'],
            'fecha_valija_del' => ['required', 'before_or_equal:today'],
            'delegacion' => ['required'],
            'comment' => ['max:450'],
        ];
    }

    public function messages()
    {
        return [
            'archivo.required' => 'Adjuntar un archivo PDF con el oficio es obligatorio',
            'archivo.mimes' => 'Archivo debe ser de formato: pdf',
            'archivo.size' => 'El tamaño de Archivo debe ser menor de :size kilobytes',
            'num_oficio_ca.required' => 'Núm. del Área de Gestión es un campo obligatorio',
            'num_oficio_ca.max' => 'Núm. del Área de Gestión debe tener menos de :max caracteres',
            'fecha_recepcion_ca.before_or_equal' => 'Fecha de Recepción en Gestión debe ser anterior o igual al día de hoy',
            'fecha_recepcion_ca.required' => 'Fecha de Recepción en Gestión es dato obligatorio.',
            'num_oficio_del.required' => 'Núm. del Oficio es un campo obligatorio',
            'num_oficio_del.max' => 'Núm. del Oficio debe tener menos de :max caracteres',
            'fecha_valija_del.before_or_equal' => 'Fecha del Oficio debe ser anterior o igual al día de hoy',
            'fecha_valija_del.required' => 'Fecha del Oficio es dato obligatorio.',
            'comment.max' => 'Comentario debe tener menos de :max caracteres',
        ];
    }

    public function attributes()
    {
        return [
            'archivo' => 'Archivo',
            'num_oficio_ca' => 'Núm. del Área de Gestión',
            'num_oficio_del' => 'Núm. del Oficio',
        ];
    }
}
