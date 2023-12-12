<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DeviceRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $validate = [
            'name'      => 'required',
            'brand'     => 'required',
            'type'      => 'required',
            'hostname'  => 'required',
            'sn'        => 'required',
        ];

        if ($request->request_method === 'update')
            $validate['status'] = 'required';

        return $validate;
    }

    public function messages()
    {
        return [];
    }
}
