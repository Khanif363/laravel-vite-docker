<?php

namespace App\Console\Commands;

use App\Helpers\Log;
use App\Models\Pending;
use App\Models\TroubleTicket;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log as LogFacades;

class AutoPendingTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:auto-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto pending ticket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        LogFacades::info('Auto pending ticket Start');

        $start = Carbon::now();
        $success = 0;
        $failed = 0;

        try {
            $data = TroubleTicket::where('status', 'Open')->get();

            foreach ($data as $datum) {
                try {
                    $lastProgress = $datum->troubleTicketProgress()
                        ->create([
                            'update_type' => 'Pending'
                        ]);

                    $lastProgress->pending()
                        ->create([
                            'pending_by' => 'By Engineer',
                            'duration' => null,
                            'reason' => 'Auto Pending Ticket By System'
                        ]);

                    $datum->update([
                        'status' => 'Pending',
                        'last_progress_id' => $lastProgress->id,
                        'last_updated_date' => now()
                    ]);

                    $success++;
                } catch (Exception $e) {
                    Log::exception($e, __METHOD__);

                    $failed++;
                }
            }
        } catch (Exception $e) {
            Log::exception($e, __METHOD__);
        } finally {
            $finish = Carbon::now();

            LogFacades::info("Result Auto pending ticket:\nSuccess: $success\nFailed: $failed");
            LogFacades::info("Auto pending ticket Done ($start - $finish | " . $start->diffInRealSeconds($finish) . " seconds)");
        }
    }
}
