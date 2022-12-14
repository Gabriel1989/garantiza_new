<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoDocumentoRequest extends FormRequest
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
            'extension' => 'required|max:5',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Debe ingresar el nombre del Tipo de Documento.',
            'name.max' => 'El nombre del Tipo de Documento no puede superar los 100 caracteres.',
            'extension.required' => 'Debe ingresar la extensión del Documento.',
            'extension.max' => 'La extensión del Documento no puede superar los 5 caracteres.',
        ];
    }
}
