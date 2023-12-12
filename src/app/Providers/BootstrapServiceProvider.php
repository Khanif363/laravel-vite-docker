<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Services\Dashboard\DashboardServiceInterface::class, \App\Services\Dashboard\DashboardService::class);
        $this->app->bind(\App\Services\TroubleTicket\TroubleTicketServiceInterface::class, \App\Services\TroubleTicket\TroubleTicketService::class);
        $this->app->bind(\App\Services\Department\DepartmentServiceInterface::class, \App\Services\Department\DepartmentService::class);
        $this->app->bind(\App\Services\Service\ServiceServiceInterface::class, \App\Services\Service\ServiceService::class);
        $this->app->bind(\App\Services\ResumeClosing\ResumeClosingServiceInterface::class, \App\Services\ResumeClosing\ResumeClosingService::class);
        $this->app->bind(\App\Services\Device\DeviceServiceInterface::class, \App\Services\Device\DeviceService::class);
        $this->app->bind(\App\Services\EmailReceiver\EmailReceiverServiceInterface::class, \App\Services\EmailReceiver\EmailReceiverService::class);
        $this->app->bind(\App\Services\UserManagement\UserManagementServiceInterface::class, \App\Services\UserManagement\UserManagementService::class);
        $this->app->bind(\App\Services\UserManagementServiceInterface::class, \App\Services\UserManagementService::class);
        $this->app->bind(\App\Services\RoleServiceServiceInterface::class, \App\Services\RoleServiceService::class);
        $this->app->bind(\App\Services\RoleServiceInterface::class, \App\Services\RoleService::class);
        $this->app->bind(\App\Services\Role\RoleServiceInterface::class, \App\Services\Role\RoleService::class);
        $this->app->bind(\App\Services\ProblemManage\ProblemManageServiceInterface::class, \App\Services\ProblemManage\ProblemManageService::class);
        $this->app->bind(\App\Services\ChangeManage\ChangeManageServiceInterface::class, \App\Services\ChangeManage\ChangeManageService::class);
        $this->app->bind(\App\Services\ResumeCloseServiceInterface::class, \App\Services\ResumeCloseService::class);
        $this->app->bind(\App\Services\ResumeClose\ResumeCloseServiceInterface::class, \App\Services\ResumeClose\ResumeCloseService::class);
        $this->app->bind(\App\Services\Catalog\CatalogServiceInterface::class, \App\Services\Catalog\CatalogService::class);
        $this->app->bind(\App\Services\User3EasyServiceServiceInterface::class, \App\Services\User3EasyServiceService::class);
        $this->app->bind(\App\Services\User3Easy\User3EasyServiceServiceInterface::class, \App\Services\User3Easy\User3EasyServiceService::class);
        $this->app->bind(\App\Services\User3Easy\User3EasyServiceInterface::class, \App\Services\User3Easy\User3EasyService::class);
        $this->app->bind(\App\Services\Location\LocationServiceInterface::class, \App\Services\Location\LocationService::class);
        $this->app->bind(\App\Services\LogActivity\LogActivityServiceInterface::class, \App\Services\LogActivity\LogActivityService::class);
        //:end-bindings:
    }
}
