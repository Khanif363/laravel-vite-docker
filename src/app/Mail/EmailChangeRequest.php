<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailChangeRequest extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): EmailChangeRequest
    {
        $email = $this
            ->subject($this->data['subject'] ?? '')
            ->with($this->data['data'] ?? [])
            ->view($this->data['view'] ?? 'emails.change-request');

        foreach ($this->data['data']['attachments_non_pict'] as $key => $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $attachmentName = "file" . $key + 1 . "." . $extension; // Nama file yang digunakan dalam email

            $mime = '';

            if ($extension === 'pdf') {
                $mime = 'application/pdf';
            } elseif ($extension === 'xls') {
                $mime = 'application/vnd.ms-excel';
            } elseif ($extension === 'xlsx') {
                $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            } elseif ($extension === 'doc') {
                $mime = 'application/msword';
            } elseif ($extension === 'docx') {
                $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            }

            $fileContents = file_get_contents(str_replace('\\', '/', public_path('storage/' . $file)));

            $email->attachData($fileContents, $attachmentName, [
                'mime' => $mime,
            ]);
        }

        return $email;
    }
}
