<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSolModSeqRequest extends FormRequest
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
            'archivo_ine' => ['required', 'file', 'mimes:pdf', 'between:100,8000'],
            'archivo_comp_dom' => ['required', 'file', 'mimes:pdf', 'between:100,8000'],
            'archivo_responsiva' => ['required', 'file', 'mimes:pdf', 'between:100,8000'],
            'fecha_solicitud' => ['required', 'before_or_equal:today'],
            'rol' => ['required', Rule::in(['1', '2', '3'])],
            'subdelegacion' => ['required'],
            'primer_apellido' => ['required', 'max:32'],
            'segundo_apellido' => ['max:32'],
            'nombre' => ['required', 'max:32'],
            'matricula' => ['required_if:tipo_movimiento,==,1,3', 'max:9', 'regex:/^(SIN DATO|\d{7,10}|INFONAVIT|TTD)$/'],
            'curp' => ['regex:/^(SIN DATO|[A-Z]{1}(A|E|I|O|U|X)[A-Z]{2}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1})$/',],
            'usuario' => ['required', 'min:6', 'max:10'],
            'email' => ['max:32'],
            'comment' => ['max:190'],
        ];
    }

    public function messages()
    {
        return [
            'archivo_ine.required' => 'Adjuntar un archivo PDF de la credencial INE ambos lados',
            'archivo_ine.mimes' => 'Archivo debe ser de formato: pdf',
            'archivo_comp_dom.required' => 'Adjuntar un archivo PDF del comprobante de domicilio',
            'archivo_comp_dom.mimes' => 'Archivo debe ser de formato: pdf',
            'archivo_responsiva.required' => 'Adjuntar un archivo PDF de la responsiva firmada',
            'archivo_responsiva.mimes' => 'Archivo debe ser de formato: pdf',
//            'archivo.size' => 'El tamaño de Archivo debe ser menor de :size kilobytes',
            'fecha_solicitud.before_or_equal' => 'Fecha de Solicitud debe ser anterior o igual al día de hoy',
            'fecha_solicitud.required' => 'Fecha de Solicitud es dato obligatorio.',
            'rol.required' => 'Rol es obligatorio',
            'rol.in' => 'Debe elegir un Rol',
            'subdelegacion.required' => 'Debe elegir un valor para Subdelegación',
            'primer_apellido.required' => 'Primer Apellido es un campo obligatorio',
            'primer_apellido.max' => 'Primer Apellido debe tener menos de :max caracteres',
            'segundo_apellido.max' => 'Segundo Apellido debe tener menos de :max caracteres',
            'nombre.required' => 'Nombre es un campo obligatorio',
            'nombre.max' => 'Nombre debe tener menos de :max caracteres',
            'matricula.required' => 'Matrícula es un campo obligatorio',
            'matricula.max' => 'Matrícula debe tener menos de :max caracteres',
            'matricula.regex' => 'Matrícula inválida. Para TTD, puede capturar SIN DATO',
            'curp.required' => 'CURP es un campo obligatorio',
            'curp.size' => 'CURP debe contener :size caracteres',
            'curp.regex' => 'CURP inválida',
            'usuario.required' => 'Usuario es un campo obligatorio',
            'usuario.max' => 'Usuario debe tener menos de :max caracteres',
            'usuario.min' => 'Usuario debe tener al menos :min caracteres',
            'email.required' => 'Correo electrónico IMSS es un campo obligatorio',
            'comment.max' => 'Comentario debe tener menos de :max caracteres',
        ];
    }

    public function attributes()
    {
        return [
            'archivo_ine' => 'Archivo INE',
            'archivo_comp_dom' => 'Archivo Comprobante Domicilio',
            'archivo_responsiva' => 'Archivo Responsiva',
            'curp' => 'CURP',
            'rol' => 'Rol',
            'email' => 'Correo electrónico',
            'matricula' => 'Matrícula',
        ];
    }
}
