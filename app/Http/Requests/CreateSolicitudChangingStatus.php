<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSolicitudChangingStatus extends FormRequest
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
            'final_remark' => ['max:190'],
        ];
    }

    public function messages()
    {
        return [
            'final_remark.max' => 'Observaciones para el cambio de estatus debe tener menos de :max caracteres',
        ];
    }

    public function attributes()
    {
        return [
        ];
    }

}
