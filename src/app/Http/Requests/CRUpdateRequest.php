<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CRUpdateRequest extends FormRequest
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
            'progress_type'=>'required',
            'content'=>'required',
            'image_proof'           => 'required|max:5',
            'image_proof.*'         => 'mimes:gif,jpg,png,pdf,jpeg,xls,xlsx,doc,docx|max:1024'
        ];
    }

    public function messages()
    {
        return [
            'progress_type.required'      => 'Jenis Update harus diisi!',
            'content.required'      => 'Keterangan harus diisi!',
            'image_proof.required'              => 'Attachment harus diisi!',
            'image_proof.max'                   => 'Maximal 5 file',
            'image_proof.*.max'                 => 'Maximal ukuran file 1 MB',
            'image_proof.*.mimes'               => 'Format file tidak didukung!',
        ];
    }
}
