<?php

namespace App\Console\Commands;

use App\Helpers\Log;
use App\Services\TroubleTicket\TroubleTicketService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as LogFacades;
use Illuminate\Support\Facades\Mail;

class EmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:notification {--isReminderClose=} {--isRating=} {--isToGM=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email Notification';

    private $troubleTicketService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TroubleTicketService $troubleTicketService)
    {
        parent::__construct();

        $this->troubleTicketService = $troubleTicketService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        LogFacades::info('Send Email Notification Start');

        $start = Carbon::now();
        $success = 0;
        $failed = 0;

        try {
            if ($this->option('isToGM')) {
                $this->toGM($success, $failed);
            }
            if ($this->option('isReminderClose')) {
                $this->_reminderClose($success, $failed);
            }
            if ($this->option('isRating')) {
                $this->_rating($success, $failed);
            }
        } catch (Exception $e) {
            Log::exception($e, __METHOD__);
        } finally {
            $finish = Carbon::now();

            LogFacades::info("Result Send Email Notification:\nSuccess: $success\nFailed: $failed");
            LogFacades::info("Send Email Notification Done ($start - $finish | " . $start->diffInRealSeconds($finish) . " seconds)");
        }
    }

    private function toGM(&$success, &$failed)
    {
        $allData = $this->troubleTicketService->getTicketAfterOneHourCreated();

        foreach ($allData as $value) {
            $emails = [];

            // if (env('APP_ENV', 'development') === 'production') :
            $emails = [env('EMAIL_GM')];
            // endif;

            try {
                DB::beginTransaction();

                $this->troubleTicketService->notifTicket($value, $emails);
                $this->troubleTicketService->updateStatusNotifGM($value->id);

                DB::commit();

                $success++;
            } catch (Exception $e) {
                DB::rollBack();

                Log::exception($e, __METHOD__);

                $failed++;
            }
        }
    }

    private function _reminderClose(&$success, &$failed)
    {
        $allData = $this->troubleTicketService->getTicketWaitingClose();

        foreach ($allData as $value) {
            $reminderCount = $value->confirmation_count + 1;

            $data = [
                'subject' => "Reminder {$reminderCount} [{$value->nomor_ticket}]: {$value->subject}",
                'view' => 'emails.notification-customer',
                'data' => [
                    'title' => $value->subject,
                    'type' => 'closed',
                    'ticket' => $value->nomor_ticket,
                    'subject' => $value->subject
                ]
            ];

            $email = $value->ticketInfo->email;

            try {
                DB::beginTransaction();
                if ($reminderCount === 3) {
                    $this->troubleTicketService->updateClosed($value->id);
                }
                $this->troubleTicketService->updateStatusConfirmTicket($value->id);

                Mail::to($email)->send(new \App\Mail\EmailNotification($data));

                DB::commit();

                $success++;
            } catch (Exception $e) {
                DB::rollBack();

                Log::exception($e, __METHOD__);

                $failed++;
            }
        }
    }

    private function _rating(&$success, &$failed)
    {
        $allData = $this->troubleTicketService->getRatingNull();

        foreach ($allData as $value) {
            $this->troubleTicketService->sendEmailRating($value, $success, $failed);
        }
    }
}
