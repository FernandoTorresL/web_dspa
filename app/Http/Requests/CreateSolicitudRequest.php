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
            'archivo' => ['required'],
            'fecha_solicitud' => ['required', 'before_or_equal:today'],
            'tipo_movimiento' => ['required', Rule::in(['1', '2', '3'])],
            'subdelegacion' => ['required'],
            'primer_apellido' => ['required', 'max:32'],
            'segundo_apellido' => ['max:32'],
            'nombre' => ['required', 'max:32'],
            'matricula' => ['required_if:tipo_movimiento,==,1,3', 'max:9', 'regex:/^(SIN DATO|\d{7,10}|INFONAVIT|TTD)$/'],
            'curp' => ['regex:/^(SIN DATO|[A-Z]{1}(A|E|I|O|U)[A-Z]{2}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1})$/',],
            'cuenta' => ['required', 'max:8'],
            'gpo_actual' => ['required_if:tipo_movimiento,==,2,3'],
            'gpo_nuevo' => ['required_if:tipo_movimiento,==,1,3'],
            'comment' => ['max:4'],
        ];
    }

    public function messages()
    {
        return [
            'archivo.required' => 'El archivo PDF del formato es obligatorio',
            'fecha_solicitud.before_or_equal' => 'Debe ser una fecha anterior o igual a hoy',
            'fecha_solicitud.required' => 'Fecha de solicitud es dato obligatorio.',
            'tipo_movimiento.required' => 'Debe elegir un valor',
            'subdelegacion.required' => 'Debe elegir un valor',
            'primer_apellido.required' => 'Es un campo obligatorio',
            'primer_apellido.max' => 'Debe tener menos de :max caracteres',
            'segundo_apellido.max' => 'Debe tener menos de :max caracteres',
            'nombre.required' => 'Es un campo obligatorio',
            'nombre.max' => 'Debe tener menos de :max caracteres',
            'matricula.required' => 'Es un campo obligatorio',
            'matricula.max' => 'Debe tener menos de :max caracteres',
            'matricula.regex' => 'Matrícula inválida. Para BAJA, puede capturar SIN DATO',
            'curp.required' => 'Es un campo obligatorio',
            'curp.size' => 'Debe contener :size caracteres',
            'curp.regex' => 'CURP inválida. Para BAJA, puede capturar SIN DATO',
            'cuenta.required' => 'Es un campo obligatorio',
            'cuenta.max' => 'Debe tener menos de :max caracteres',
            'gpo_actual.required_if' => 'Grupo Actual es obligatorio cuando Tipo de Movimiento es BAJA.',
            'gpo_nuevo.required_if' => 'Grupo Nuevo es obligatorio cuando Tipo de Movimiento es ALTA o CAMBIO.',
            'comment.max' => 'Debe tener menos de :max caracteres',
        ];
    }

    public function attributes()
    {
        return [
            'archivo' => 'Archivo',
            'curp' => 'CURP',
            'tipo movimiento' => 'Tipo de Movimiento',
        ];
    }
}
