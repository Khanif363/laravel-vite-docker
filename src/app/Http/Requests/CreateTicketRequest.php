<?php

namespace App\Http\Requests;

use App\Services\TroubleTicket\TroubleTicketService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
{
    private $troubleTicketService;

    public function __construct(TroubleTicketService $troubleTicketService)
    {
        $this->troubleTicketService = $troubleTicketService;
    }
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
        if ($request->step == 1) :
            $roles = [
                'creator_id'            => 'required',
                'category'              => 'required',
                'type'                  => 'required',
                'subject'               => 'required',
                'reporter_name'         => 'required',
            ];
        else :
            switch ($request->type) {
                case 'Incident TTR':
                    $add = [
                        'reporter_name'         => 'required',
                    ];
                    break;
                default:
            }

            switch ($request->source_info_trouble) {
                case 'Nota Dinas':
                    $add = [
                        // 'reporter_name'         => 'required',
                    ];
                    break;
                default:
                    $add = [
                        // 'reporter_name'         => 'required',
                        'reporter_phone'        => 'required'
                    ];
            }

            if (in_array($request->type, ['Service Request', 'Fulfillment'])) {
                $add = [
                    'service_id'        => 'required',
                ];
            } else {
                $add = [
                    'problem_type'        => 'required',
                ];
            }

            $roles = array_merge($add ?? [], [
                // 'creator_id'            => 'required',
                'priority'              => 'required',
                // 'type'                  => 'required',
                // 'problem_type'          => 'required',
                'subject'               => 'required',
                'problem'               => 'required',
                'event_date'            => 'required',
                'event_time'            => 'required',
                'event_location_id'        => 'required',
                'source_info_trouble'   => 'required',
                'reporter_department'   => 'required',
                'detail_info'           => 'required',
                'reporter_email'        => 'required|email',
                'reporter_nik'          => 'required',
                "image_proof"           => "required|max:5",
                'image_proof.*'         => 'mimes:gif,jpg,png,pdf,jpeg,xls,xlsx,doc,docx|max:1024'
            ]);
        endif;

        return $roles;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $eventDateTime = date('Y-m-d H:i:s', strtotime($this->event_date . " " . $this->event_time));
            $ticket = null;
            if ($this->id) {
                $ticket = $this->troubleTicketService->findById($this->id) ?? null;
            }
            if ($eventDateTime > now()->toDateTimeString() || $ticket ? $eventDateTime > $ticket->created_date : false) {
                $validator->errors()->add('invalid_event_datetime', 'Coba diperhatikan kembali input waktunya..');
            }
        });
    }

    public function messages()
    {
        return [
            'priority.required'                 => 'Prioritas harus diisi!',
            'type.required'                     => 'Tipe harus diisi!',
            'category.required'                 => 'Kategori harus diisi!',
            'problem_type.required'             => 'Tipe Gangguan harus diisi!',
            'subject.required'                  => 'Subjek harus diisi!',
            'problem.required'                  => 'Keterangan harus diisi!',
            'event_date.required'               => 'Tanggal Kejadian harus diisi!',
            'event_time.required'               => 'Waktu Kejadian harus diisi!',
            'event_location_id.required'           => 'Lokasi Kejadian harus diisi!',
            'source_info_trouble.required'      => 'Sumber Info harus diisi!',
            'reporter_department.required'      => 'Department Pelapor harus diisi!',
            'detail_info.required'              => 'Detail Info harus diisi!',
            'image_proof.required'              => 'Attachment harus diisi!',
            'image_proof.max'                   => 'Maximal 5 file',
            'image_proof.*.max'                 => 'Maximal ukuran file 1 MB',
            'image_proof.*.mimes'               => 'Format file tidak didukung!',
            'reporter_name.required'            => 'Nama Pelapor harus diisi!',
            'reporter_email.required'           => 'Email Pelapor harus diisi!',
            'reporter_email.email'              => 'Email tidak valid!',
            'reporter_phone.required'           => 'Nomor Pelapor harus diisi!',
            'reporter_nik.required'             => 'NIK Pelapor harus diisi!',
            'service_id.required'               => 'Service harus diisi!',
        ];
    }
}
