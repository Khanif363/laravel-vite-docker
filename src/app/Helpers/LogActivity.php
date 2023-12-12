<?php

namespace App\Helpers;

use App\Helpers\Log;
use Illuminate\Support\Facades\DB;
use App\Services\LogActivity\LogActivityService;

class LogActivity
{
    private $mainService;

    public function __construct(LogActivityService $mainService)
    {
        $this->mainService = $mainService;
    }

    public function createLog($data)
    {
        DB::beginTransaction();
        try {
            $this->mainService->createLog($data);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::exception($ex, __METHOD__);
        }
    }
};
