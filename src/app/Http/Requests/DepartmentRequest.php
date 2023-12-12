<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'name'              => 'required',
            'is_desk'           => 'required',
            'is_core'           => 'required',
        ];
        if($request->request_method === 'update'):
            $validate['status'] = 'required';
        endif;
        return $validate;
    }

    public function messages()
    {
        return [
            'name.required'         => 'Nama Department harus diisi!',
            'status.required'       => 'Status harus diisi!',
            'is_desk.required'      => 'Pilihan Service Desk harus diisi!',
            'is_core.required'      => 'Pilihan Core harus diisi!'
        ];
    }
}
