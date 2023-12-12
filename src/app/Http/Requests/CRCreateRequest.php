<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\ChangeManage\ChangeManageService;

class CRCreateRequest extends FormRequest
{

    private $changeManageService;

    public function __construct(ChangeManageService $changeManageService)
    {
        $this->changeManageService = $changeManageService;
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
        $roles = [
            'creator_id' => 'required',
            'title' => 'required',
            'type' => 'required',
            'priority' => 'required',
            'date_action' => 'required',
            'time_action' => 'required',
            'reference' => 'required',
            'location_id' => 'required',
            'engineer_id' => 'required',
            'approval_level1_id' => 'required',
            'approval_level2_id' => $this?->priority == 'High' ? 'required' : ($this?->with_approve_gm == 1 ? 'required' : ''),
            'agenda' => 'required',
            'content' => 'required',
            // 'image_proof'           => !$this->id ? 'required|max:5' : '',
            'image_proof.*'         => 'mimes:gif,jpg,png,pdf,jpeg,xls,xlsx,doc,docx|max:5000'
        ];

        $changes = null;
        if ($this?->id) $changes = $this->changeManageService->detailCR($this?->id);
        if (!$this->id || (count($changes?->attachments) == 0 && !isset($this?->image_proof))) $roles += ['image_proof'=>'required|max:5'];

        if ($request->reference === 'Ticket') {
            $roles += ['ticket_reference' => 'required'];
        }

        return $roles;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->pic_telkomsat) && empty($this->pic_nontelkomsat)) {
                $validator->errors()->add('pic', 'Salah satu kolom PIC harus terisi!');
            }


            if ($this->id) {

                $current_changes = $this->changeManageService->findById($this->id);

                $edited = [];

                $this['title'] != $current_changes->title ? array_push($edited, "Title") : null;
                $this['type'] != $current_changes->type ? array_push($edited, "Tipe Changes") : null;
                $this['priority'] != $current_changes->priority ? array_push($edited, "Prioritas") : null;
                date('Y-m-d H:i:s', strtotime($this['date_action'] . " " . $this['time_action'])) != $current_changes->datetime_action ? array_push($edited, "Date/Time Aksi") : null;
                $this['pic_telkomsat'] != $current_changes->pic_telkomsat ? array_push($edited, "PIC Telkomsat") : null;
                $this['pic_nontelkomsat'] != $current_changes->pic_nontelkomsat ? array_push($edited, "PIC Non Telkomsat") : null;
                $this['reference'] != $current_changes->reference ? array_push($edited, "Referensi Changes") : null;
                $this['location_id'] != $current_changes->location_id ? array_push($edited, "Location") : null;
                $this['engineer_id'] != $current_changes->engineer_id ? array_push($edited, "Nama Engineer") : null;
                $this['approval_level1_id'] != $current_changes->approval_level1_id ? array_push($edited, "Persetujuan Oleh Manager") : null;
                $this['approval_level2_id'] != $current_changes->approval_level2_id ? array_push($edited, "Persetujuan Oleh GM") : null;
                $this['agenda'] != $current_changes->agenda ? array_push($edited, "Agenda") : null;
                $this['content'] != $current_changes->content ? array_push($edited, "Content") : null;
                if ($current_changes->is_draft) {
                    $this->email_to_level0 != $current_changes->email_to_level0 ? array_push($edited, "Email to Engineer") : null;
                    $this->email_to_level1 != $current_changes->email_to_level1 ? array_push($edited, "Email to Manager") : null;
                    $this->email_to_level2 != $current_changes->email_to_level2 ? array_push($edited, "Email to GM") : null;
                }
                $this['ticket_reference_id'] != $current_changes->ticket_reference_id ? array_push($edited, "Ticket Reference") : null;
                ($this['images_removed'] ?? null) ? array_push($edited, "Attachment") : null;
                if (!$edited) {
                    $validator->errors()->add('not_edit', 'Tidak ada yang diedit!');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'creator_id.required'            => 'Nama Pembuat harus diisi!',
            'ticket_reference.required'             => 'Ticket Reference harus diisi!',
            'title.required'  => 'Title harus diisi!',
            'date_action.required'         => 'Tanggal Aksi harus diisi!',
            'time_action.required'         => 'Waktu Aksi harus diisi!',
            'location_id.required'           => 'Lokasi harus diisi!',
            'engineer_id.required'           => 'Engineer harus diisi!',
            'approval_level1_id.required'           => 'Manager harus diisi!',
            'approval_level2_id.required'           => 'General Manager harus diisi!',
            'type.required'           => 'Tipe Changes harus diisi!',
            'reference.required'           => 'Reference harus diisi!',
            'priority.required' => 'Prioritas harus diisi!',
            // 'pic.required'    => 'Salah satu PIC harus diisi!',
            'agenda.required'      => 'Agenda harus diisi!',
            'content.required'      => 'Keterangan harus diisi!',
            'image_proof.required'              => 'Attachment harus diisi!',
            'image_proof.max'                   => 'Maximal 5 file',
            'image_proof.*.max'                 => 'Maximal ukuran file 1 MB',
            'image_proof.*.mimes'               => 'Format file tidak didukung!',
        ];
    }

    public function ajax()
    {
        return true;
    }
}
