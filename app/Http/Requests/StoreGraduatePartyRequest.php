<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGraduatePartyRequest extends FormRequest
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
            'escuela_id' => 'required|integer',
            'curso' => 'required',
            'fecha' => 'required',
            'dia_id' => 'required|integer',
            'menu_id' => 'required|integer',
            'forma_pago_id' => 'required|integer'
        ];
    }
}
