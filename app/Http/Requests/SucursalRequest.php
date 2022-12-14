<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SucursalRequest extends FormRequest
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
            'name' => 'required|max:45',
            'concesionaria_id' => 'required',
            'region' => 'required|integer',
            'comuna' => 'required|integer',
            'calle' => 'required|max:45',
            'numero' => 'required|max:9',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Debe ingresar el nombre de la Sucursal.',
            'name.max' => 'El nombre de la Sucursal no puede superar los 45 caracteres.',
            'concesionaria_id.required' => 'Debe seleccionar la concesionaria.',
            'region.required' => 'Debe ingresar la región de la Sucursal.',
            'region.integer' => 'La región debe ser numérica.',
            'comuna.required' => 'Debe ingresar la comuna de la Sucursal.',
            'comuna.integer' => 'La comuna debe ser numérica.',
            'calle.required' => 'Debe ingresar la dirección de la Sucursal.',
            'calle.max' => 'El calle de la dirección no puede superar los 45 caracteres.',
            'numero.required' => 'Debe ingresar el número de la dirección de la Sucursal.',
            'numero.max' => 'El número de la dirección no puede superar los 9 caracteres.',
        ];
    }
}
