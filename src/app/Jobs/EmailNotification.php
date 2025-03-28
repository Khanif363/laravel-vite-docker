<?php

namespace App\Jobs;

use App\Helpers\Log;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log as LogFacades;
use Illuminate\Support\Facades\Mail;

class EmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $_data, $_email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $data)
    {
        $this->_email = $email;
        $this->_data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        LogFacades::info('Job Send Email Notification Start');

        $start = Carbon::now();
        $isSuccess = false;

        try {
            Mail::to($this->_email)->send(new \App\Mail\EmailNotification($this->_data));

            $isSuccess = true;
        } catch (Exception $e) {
            Log::exception($e, __METHOD__);
        } finally {
            $finish = Carbon::now();

            $result = $isSuccess ? 'Success' : 'Failed';

            LogFacades::info("Result Job Send Email Notification: $result");
            LogFacades::info("Job Send Email Notification Done ($start - $finish | " . $start->diffInRealSeconds($finish) . " seconds)");
        }
    }
}
