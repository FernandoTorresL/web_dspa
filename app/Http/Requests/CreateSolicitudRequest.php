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
            'fecha_solicitud_del' => ['required'],
            'tipo_movimiento' => ['required', Rule::in(['ALTA', 'BAJA', 'CAMBIO'])],
            'subdelegacion' => ['required'],
            'primer_apellido' => ['required', 'max:32'],
            'segundo_apellido' => ['max:32'],
            'nombre' => ['required', 'max:32'],
            'matricula' => ['required_if:tipo_movimiento,==,ALTA,CAMBIO', 'max:8'],
            'curp' => [
                'required_if:tipo_movimiento,==,CAMBIO,ALTA,CAMBIO|size:18|regex:/^[A-Z]{1}(A|E|I|O|U)[A-Z]{2}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1}$/',
                ],
//            'cuenta' => ['required', 'max:8'],
            'cuenta' => ['required_if:tipo_movimiento,==,CAMBIO'],
            'gpo_actual' => ['required_if:tipo_movimiento,==,BAJA,CAMBIO'],
            'gpo_nuevo' => ['required_if:tipo_movimiento,==,ALTA,CAMBIO'],
            'comment' => ['max:4'],
        ];
    }

    public function solicitudes()
    {
        return [
            'fecha_solicitud_del.required' => 'Fecha de solicitud es dato obligatorio.',

            'tipo_movimiento.required' => 'Debe elegir un valor',
            'subdelegacion.required' => 'Debe elegir un valor',
            'primer_apellido.required' => 'Es un campo obligatorio',
            'primer_apellido.max' => 'Debe tener menos de :max caracteres',
            'segundo_apellido.max' => 'Debe tener menos de :max caracteres',
            'nombre.required' => 'Es un campo obligatorio',
            'nombre.max' => 'Debe tener menos de :max caracteres',
            'matricula.required' => 'Es un campo obligatorio',
            'matricula.max' => 'Debe tener menos de :max caracteres',
            'curp.required' => 'Es un campo obligatorio',
            'curp.size' => 'Debe contener :size caracteres',
            'curp.regex' => 'No es una CURP válida',
            'cuenta.required' => 'Es un campo obligatorio',
            'cuenta.max' => 'Debe tener menos de :max caracteres',
            'gpo_actual' => 'Hola',
            'comment.max' => 'Debe tener menos de :max caracteres',
        ];
    }
}
