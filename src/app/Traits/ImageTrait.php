<?php

namespace App\Traits;

use App\Models\Attachment;
use Illuminate\Http\Request;

trait ImageTrait
{

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function verifyAndUpload($request, $fieldname = 'image', $directory = 'image')
    {
        // dd($request);
        if ($request->hasFile($fieldname)) {
            $arrName = [];
            $arrUrl = [];
            $attachment = [];
            $photo = $request->file($fieldname);
            foreach ($photo as $key => $photos) {
                if ((env('OS_DEVICE') ?? '') == 'Windows') :
                    $url = $photos->storeAs("$directory", $photos->hashName());
                else :
                    $url = $photos->storeAs("public/$directory", $photos->hashName());
                endif;
                $arrName[] = $photos->hashName();
                $arrUrl[] = $url;
                $attachment[] = Attachment::create([
                    'name'                          => $arrName[$key],
                    'url'                           => $arrUrl[$key],
                    'inputer_id'                    => $request['inputer_id_attachment'],
                    'trouble_ticket_id'             => $request['trouble_ticket_id_attachment'],
                    'trouble_ticket_progress_id'    => $request['trouble_ticket_progress_id_attachment'],
                    'profile_id'                    => $request['profile_id_attachment'],
                    'problem_manage_id'             => $request['problem_manage_id_attachment'],
                    'problem_manage_progress_id'    => $request['problem_manage_progress_id_attachment'],
                    'change_manage_id'             => $request['change_manage_id_attachment'],
                    'change_manage_progress_id'    => $request['change_manage_progress_id_attachment'],
                ]);
            }
            return $attachment;
        }
        return null;
    }
}
