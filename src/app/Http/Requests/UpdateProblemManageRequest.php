<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProblemManageRequest extends FormRequest
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
            'progress_type' => 'required',
            'information'   => 'required',
            'image_proof.*' => 'mimes:gif,jpg,png,pdf,jpeg,xls,xlsx,doc,docx|max:1024'
        ];
    }

    public function messages()
    {
        return [
            'progress_type.required'    => 'Tipe progress harus diisi!',
            'information.required'      => 'Keterangan harus diisi!',
            'image_proof.*.max'         => 'Maximal ukuran file 1 MB',
            'image_proof.*.mimes'       => 'Format file tidak didukung!',
        ];
    }
}
