<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'rol_id' => 'required',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Debe ingresar el nombre del Usuario.',
            'name.max' => 'El nombre del Usuario no puede superar los 100 caracteres.',
            'email.required' => 'Debe ingresar el email del Usuario.',
            'email.max' => 'El email del Usuario no puede superar los 100 caracteres.',
            'rol_id.required' => 'Debe seleccionar el Rol del Usuario.',
        ];
    }
}
