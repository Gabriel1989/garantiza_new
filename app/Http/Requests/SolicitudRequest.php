<?php

namespace App\Http\Requests;

use App\Rules\PPURule;
use Illuminate\Foundation\Http\FormRequest;

class SolicitudRequest extends FormRequest
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
            'sucursal_id' => 'integer|required|gt:0',
            'tipoVehiculos_id' => 'integer|required|gt:0',
        ];
    }

    public function messages(){
        return [
            'sucursal_id.required' => 'Debe seleccionar la Sucursal',
            'sucursal_id.gt' => 'Debe seleccionar la Sucursal',
            'tipoVehiculos_id.required' => 'Debe seleccionar el Tipo de Vehículo',
            'tipoVehiculos_id.gt' => 'Debe seleccionar el Tipo de Vehículo',
        ];
    }
}
