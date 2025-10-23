<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSolModSegRequest extends FormRequest
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

            'fecha_sol_mod_seg' => ['required', 'before_or_equal:today'],
            'rol' => ['required', Rule::in(['1', '2', '3'])],

            'subdelegacion' => ['required'],

            'primer_apellido' => ['required', 'max:32'],
            'segundo_apellido' => ['max:32'],

            'nombre' => ['required', 'max:32'],
            'matricula' => ['required', 'max:9', 'regex:/^(SIN DATO|\d{7,10}|TTD|PTD)$/'],

            'curp' => ['required', 'regex:/^(SIN DATO|[A-Z]{1}(A|E|I|O|U|X)[A-Z]{2}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1})$/'],
            'usuario_mod_seg' => ['required', 'min:6', 'max:10'],

            'telefono' => ['required', 'max:10', 'regex:/^(\d{8,10})$/'],
            'tel_ext' => ['required', 'max:5', 'regex:/^(\d{5,7}|SIN DATO)$/'],

            'correo' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'nombre_pc' => ['required'],
            
            'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'nombre' => ['required', 'max:50'],

            //'dir_ip' => ['required', 'ip', 'regex:/^((?:(?:25[0-5]|2[0-4][0-9]|?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|?[0-9][0-9]?))$/'],
            'dir_ip' => ['required'],
            //'dir_ip' => ['required', 'ip', 'regex:/^((?:(?:25[0-5]|2[0-4][0-9]|?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|?[0-9][0-9]?))$/'],
            'mac_address' => ['required', 'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/'],

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

            'fecha_sol_mod_seg.before_or_equal' => 'Fecha de Solicitud debe ser anterior o igual al día de hoy',
            'fecha_sol_mod_seg.required' => 'Fecha de Solicitud es dato obligatorio.',

            'rol.required' => 'Debe elegir un valor de Rol para el usuario',
            'rol.in' => 'Debe elegir un Rol',

            'subdelegacion.required' => 'Debe elegir un valor para Subdelegación',

            'primer_apellido.required' => 'Primer Apellido es un campo obligatorio',
            'primer_apellido.max' => 'Primer Apellido debe tener menos de :max caracteres',

            'segundo_apellido.max' => 'Segundo Apellido debe tener menos de :max caracteres',

            'nombre.required' => 'Nombre es un campo obligatorio',
            'nombre.max' => 'Nombre debe tener menos de :max caracteres',

            'matricula.required' => 'Matrícula es un campo obligatorio',
            'matricula.max' => 'Matrícula debe tener menos de :max caracteres',
            'matricula.regex' => 'Matrícula inválida. Para TTD/PTD, puede capturar SIN DATO',

            'curp.required' => 'CURP es un campo obligatorio',
            'curp.size' => 'CURP debe contener :size caracteres',
            'curp.regex' => 'CURP inválida',

            'usuario_mod_seg.required' => 'USUARIO es un campo obligatorio',
            'usuario_mod_seg.max' => 'USUARIO debe tener menos de :max caracteres',
            'usuario_mod_seg.min' => 'USUARIO debe tener al menos :min caracteres',
            
            'telefono.required' => 'Teléfono es un campo obligatorio',
            'telefono.size' => 'Teléfono debe contener :size caracteres',
            'telefono.regex' => 'Teléfono inválido',

            'tel_ext.required' => 'Extensión es un campo obligatorio',
            'tel_ext.size' => 'Extensión debe contener :size caracteres',
            'tel_ext.regex' => 'Extensión inválida',

            'email.required' => 'Correo electrónico IMSS es un campo obligatorio',
            'email.regex' => 'Correo electrónico IMSS inválido o incompleto',

            'nombre_pc.required' => 'Nombre del equipo|PC es un campo obligatorio',

            'dir_ip.required' => 'Dirección IP es un campo obligatorio',
            'dir_ip.size' => 'Dirección IP debe contener :size caracteres',
            'dir_ip.regex' => 'Dirección IP inválida',

            'mac_address.required' => 'Dirección física (MAC Address) es un campo obligatorio',
            'mac_address.size' => 'Dirección física (MAC Address) debe contener :size caracteres',
            'mac_address.regex' => 'Dirección física (MAC Address) inválida',

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
