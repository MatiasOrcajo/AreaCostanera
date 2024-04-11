<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialEventRequest extends FormRequest
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
            'name' => 'required|string',
            'date' => 'required',
            'diners' => 'required|integer',
            'menu_id' => 'required|integer'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'El campo Nombre es obligatorio',
            'name.string' => 'El campo Nombre debe ser texto',
            'diners.required' => 'El campo Comensales es obligatorio',
            'diners.integer' => 'El campo Comensales debe ser numérico',
            'menu_id.required' => 'El campo Menu es obligatorio',
            'date.required' => 'El campo Fecha es obligatorio',
        ];
    }

}
