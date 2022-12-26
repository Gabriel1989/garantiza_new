<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PPURequest extends FormRequest
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
            'region' => 'integer|required|gt:0',
            'placa_patente_id' => 'string|required',
            'nombre_institucion' => 'string|required|max:10'
        ];
    }

    public function messages(){
        return [
            'region.required' => 'Debe seleccionar la Región',
            'region.gt' => 'Debe seleccionar la Región',
            'placa_patente_id.required' => 'Debe seleccionar el Tipo de Placa',
            'placa_patente_id.string' => 'Debe seleccionar el Tipo de Placa',
            'nombre_institucion.required' => 'Debe ingresar un nombre de institución',
            'nombre_institucion.string' => 'El nombre de institución debe ser un texto',
            'nombre_institucion.max:10' => 'El nombre de institución debe tener máximo 10 caracteres'
        ];
    }
}