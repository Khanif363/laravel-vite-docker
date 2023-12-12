<?php

namespace App\Services\LogActivity;

use App\Repositories\LogActivity\LogActivityRepository;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Services\LogActivity\LogActivityServiceInterface;
use Illuminate\Support\Facades\Log;

class LogActivityService implements LogActivityServiceInterface
{
    private $location;
    private $request;
    private $mainRepository;

    public function __construct(LogActivityRepository $mainRepository, Location $location, Request $request)
    {
        $this->location = $location;
        $this->request = $request;
        $this->mainRepository = $mainRepository;
    }

    public function createLog(array $data)
    {
        $agent = new Agent();
        $location = $this->location::get($this->request->ip()) ?? null;
        $browser = $agent->browser();
        $browser_version = $agent->version($browser);
        $os = $agent->platform();
        $os_version = $agent->version($os);

        $log = [
            'user_id' => auth()->id() ?? null,
            'ip_address' => $this->request->ip(),
            'browser' => $browser . ' ' . $browser_version,
            'os' => $os . ' ' . $os_version,
            'location' => !empty($location) ? $location->cityName . ', ' . $location->countryName : null,
            'description' => $data['description'] ?? null,
            'url' => $this->request->fullUrl(),
            'method' => $this->request->method(),
            'status' => $data['status'] ?? null,
            'error_message' => $data['error_message'] ?? null,
        ];

        return $this->mainRepository->createLog($log);
    }
}
