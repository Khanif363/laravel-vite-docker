<?php

namespace App\Http\Controllers\Panel;

use App\Traits\ImageTrait;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    use ImageTrait;

    public function index()
    {
        return view('pages.upload.index');
    }

    public function uploadImage(Request $request)
    {

        try {
            if ($request->hasFile('image_proof_rev')) :
                $request['inputer_id_attachment'] = auth()->id();
                $this->verifyAndUpload($request, 'image_proof_rev', 'image_proof_rev');
            endif;
            return back()->with('success', 'Image berhasil di upload');
        } catch (\Exception $ex) {
            return back()->with('success', $ex->getMessage());
        }
    }
}
