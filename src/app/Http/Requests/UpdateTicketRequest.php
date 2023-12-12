<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
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
        switch ($request->update_type) {
            case 'Diagnose':
                $add = [
                    'department_id'     => 'required',
                    'service_id'        => 'required',
                    'content'           => 'required',
                    // 'need_engineer'           => 'required',
                ];
                if($request->department_id == 2) {
                    $add += ['mantools_datacom_id'=> 'required'];
                }
                break;
            case 'Dispatch':
                $add = [
                    'department_to_id'  => 'required'
                ];
                break;
            case 'Engineer Assignment':
                $add = [
                    'engineer_assignment_id'    => 'required',
                    'close_estimation_date'     => 'required',
                    'close_estimation_time'     => 'required',
                ];
                break;
            case 'Engineer Troubleshoot':
                $add = [
                    'content'           => 'required',
                ];
                break;
            case 'Pending':
                $add = [
                    'pending_by'    => 'required',
                    'duration_date'    => 'required',
                    'duration_time'    => 'required',
                ];
                break;
            case 'Technical Close':
                $add = [
                    'resume_id'                 => 'required',
                    'rfo'                       => 'required',
                    'solution'                  => 'required',
                    'evaluation'                => 'required',
                    'is_visited'                => 'required',
                    'image_proof'               => 'required|max:5',
                    'image_proof.*'             => 'mimes:gif,jpg,png,pdf,jpeg,xls,xlsx,doc,docx|max:1024'
                ];
                break;
            case 'Monitoring':
                $add = [
                    // 'content'           => 'required',
                ];
                break;
            case 'Closed':
                $add = [
                    // 'rate'              => 'min:0|max:100',
                    'image_proof'       => 'required',
                    'content'           => 'required',
                    'customer_confirm'           => 'required',
                ];
                break;
        }


        $roles = array_merge($add ?? [], [
            'update_type'       => 'required',
            'image_proof.*'     => 'mimes:gif,jpg,png,pdf,jpeg,xls,xlsx|max:1024'
        ]);

        // dd($roles);
        return $roles;
    }

    public function messages()
    {
        return [
            'update_type.required'      => 'Jenis Update harus diisi!',
            'content.required'          => 'Keterangan harus diisi!',
            'rate.min'                  => 'Rating minimal 0!',
            'rate.max'                  => 'Rating maximal 100!',
            'image_proof.required'      => 'Attachment harus diisi!',
            'image_proof.max'           => 'Maximal 5 file',
            'image_proof.*.max'         => 'Maximal ukuran file 1 MB',
            'image_proof.*.mimes'       => 'Format file tidak didukung!',
            'department_id.required'    => 'Department harus diisi!',
            'service_id.required'       => 'Service harus diisi!',
            'close_estimation_date.required'    => 'Tanggal estimasi selesai harus diisi!',
            'close_estimation_time.required'    => 'Waktu estimasi selesai harus diisi!',
            'department_to_id.required'         => 'Department dispatch harus diisi!',
            'engineer_assignment_id.required'   => 'Engineer harus diisi!',
            'pending_by.required'               => 'Pending By harus diisi!',
            'duration_date.required'                 => 'Tanggal Durasi pending harus diisi!',
            'duration_time.required'                 => 'Waktu Durasi pending harus diisi!',
            'rate.required'                     => 'Rating customer harus diisi!',
            'resume_id.required'                        => 'Tindakan harus diisi!',
            'rfo.required'                              => 'RFO harus diisi!',
            'solution.required'                         => 'Solution harus diisi!',
            'evaluation.required'                       => 'Evaluasi harus diisi!',
            'is_visited.required'                       => 'Visit Lokasi harus diisi!',
            'mantools_datacom_id.required'              => 'Data Backhaul harus diisi!',
            // 'need_engineer.required'                    => 'Status kebutuhan engineer harus diisi!',
        ];
    }
}
