<?php

namespace App\Console\Commands;

use App\Helpers\Log;
use App\Models\Pending;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log as LogFacades;

class AutoOpenTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:auto-open {--isEveryMoring=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto open ticket';

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
        LogFacades::info('Auto open ticket Start');

        $start = Carbon::now();
        $success = 0;
        $failed = 0;

        try {
            $data = Pending::with('troubleTicketProgress.troubleTicket')
                ->whereHas('troubleTicketProgress', function ($query) {
                    $query->where('update_type', 'Pending');
                })
                ->whereHas('troubleTicketProgress.troubleTicket', function ($query) {
                    $query->where('status', 'Pending');
                })
                ->whereNull('closed_at')
                ->get();

            $now = Carbon::now();

            foreach ($data as $datum) {
                try {
                    if (!$this->option('isEveryMoring')) {
                        if (!$datum->duration) continue;

                        $dueTime = $datum->created_at->addSeconds($datum->duration);

                        if ($now->lessThan($dueTime)) continue;
                    }
                    if ($datum->duration) continue;

                    $datum->troubleTicketProgress->troubleTicket->update([
                        'status' => 'Open'
                    ]);
                    $datum->update([
                        'closed_at' => Carbon::now()
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

            LogFacades::info("Result Auto open ticket:\nSuccess: $success\nFailed: $failed");
            LogFacades::info("Auto open ticket Done ($start - $finish | " . $start->diffInRealSeconds($finish) . " seconds)");
        }
    }
}
