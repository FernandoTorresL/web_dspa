<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSolicitudRequest extends FormRequest
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
            'fecha_solicitud' => ['required', 'before_or_equal:today'],
            'tipo_movimiento' => ['required', Rule::in(['1', '2', '3'])],
            'subdelegacion' => ['required'],
            'primer_apellido' => ['required', 'max:32'],
            'segundo_apellido' => ['max:32'],
            'nombre' => ['required', 'max:32'],
            'matricula' => ['required_if:tipo_movimiento,==,1,3', 'max:9', 'regex:/^(SIN DATO|\d{7,10}|INFONAVIT|TTD)$/'],
            'curp' => ['regex:/^(SIN DATO|[A-Z]{1}(A|E|I|O|U)[A-Z]{2}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1})$/',],
            'cuenta' => ['required', 'max:7'],
            'gpo_actual' => ['required_if:tipo_movimiento,==,2,3'],
            'gpo_nuevo' => ['required_if:tipo_movimiento,==,1,3'],
            'comment' => ['max:190'],
        ];
    }

    public function messages()
    {
        return [
            'archivo.required' => 'Adjuntar un archivo PDF con el formato y tarjetón es obligatorio',
            'archivo.mimes' => 'Archivo debe ser de formato: pdf',
//            'archivo.size' => 'El tamaño de Archivo debe ser menor de :size kilobytes',
            'fecha_solicitud.before_or_equal' => 'Fecha de Solicitud debe ser anterior o igual al día de hoy',
            'fecha_solicitud.required' => 'Fecha de Solicitud es dato obligatorio.',
            'tipo_movimiento.required' => 'Tipo de Movimiento es obligatorio',
            'tipo_movimiento.in' => 'Debe elegir un Tipo de Movimiento',
            'subdelegacion.required' => 'Debe elegir un valor para Subdelegación',
            'primer_apellido.required' => 'Primer Apellido es un campo obligatorio',
            'primer_apellido.max' => 'Primer Apellido debe tener menos de :max caracteres',
            'segundo_apellido.max' => 'Segundo Apellido debe tener menos de :max caracteres',
            'nombre.required' => 'Nombre es un campo obligatorio',
            'nombre.max' => 'Nombre debe tener menos de :max caracteres',
            'matricula.required' => 'Matrícula es un campo obligatorio',
            'matricula.max' => 'Matrícula debe tener menos de :max caracteres',
            'matricula.regex' => 'Matrícula inválida. Para BAJA, puede capturar SIN DATO',
            'curp.required' => 'CURP es un campo obligatorio',
            'curp.size' => 'CURP debe contener :size caracteres',
            'curp.regex' => 'CURP inválida. Para BAJA, puede capturar SIN DATO',
            'cuenta.required' => 'User-ID es un campo obligatorio',
            'cuenta.max' => 'User-ID debe tener menos de :max caracteres',
            'gpo_actual.required_if' => 'Elija un valor cuando Tipo de Movimiento es BAJA o CAMBIO.',
            'gpo_nuevo.required_if' => 'Elija un valor cuando Tipo de Movimiento es ALTA o CAMBIO.',
            'gpo_actual.min' => 'Requerido si Tipo de Movimiento es BAJA.',
            'gpo_nuevo.min' => 'Requerido si Tipo de Movimiento es ALTA o CAMBIO.',
            'comment.max' => 'Comentario debe tener menos de :max caracteres',
        ];
    }

    public function attributes()
    {
        return [
            'archivo' => 'Archivo',
            'curp' => 'CURP',
            'tipo movimiento' => 'Tipo de Movimiento',
            'matricula' => 'Matrícula',
        ];
    }
}
