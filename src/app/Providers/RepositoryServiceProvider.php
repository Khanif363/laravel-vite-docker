<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Repositories\Dashboard\DashboardRepositoryInterface::class, \App\Repositories\Dashboard\DashboardRepository::class);
        $this->app->bind(\App\Repositories\TroubleTicket\TroubleTicketRepositoryInterface::class, \App\Repositories\TroubleTicket\TroubleTicketRepository::class);
        $this->app->bind(\App\Repositories\Department\DepartmentRepositoryInterface::class, \App\Repositories\Department\DepartmentRepository::class);
        $this->app->bind(\App\Repositories\Service\ServiceRepositoryInterface::class, \App\Repositories\Service\ServiceRepository::class);
        $this->app->bind(\App\Repositories\ResumeClosing\ResumeClosingRepositoryInterface::class, \App\Repositories\ResumeClosing\ResumeClosingRepository::class);
        $this->app->bind(\App\Repositories\Device\DeviceRepositoryInterface::class, \App\Repositories\Device\DeviceRepository::class);
        $this->app->bind(\App\Repositories\EmailReceiver\EmailReceiverRepositoryInterface::class, \App\Repositories\EmailReceiver\EmailReceiverRepository::class);
        $this->app->bind(\App\Repositories\UserManagement\UserManagementRepositoryInterface::class, \App\Repositories\UserManagement\UserManagementRepository::class);
        $this->app->bind(\App\Repositories\RoleRepositoryRepositoryInterface::class, \App\Repositories\RoleRepositoryRepository::class);
        $this->app->bind(\App\Repositories\RoleRepositoryInterface::class, \App\Repositories\RoleRepository::class);
        $this->app->bind(\App\Repositories\Role\RoleRepositoryInterface::class, \App\Repositories\Role\RoleRepository::class);
        $this->app->bind(\App\Repositories\ProblemManageRepositoryInterface::class, \App\Repositories\ProblemManageRepository::class);
        $this->app->bind(\App\Repositories\ProblemManage\ProblemManageRepositoryInterface::class, \App\Repositories\ProblemManage\ProblemManageRepository::class);
        $this->app->bind(\App\Repositories\ChangeManage\ChangeManageRepositoryInterface::class, \App\Repositories\ChangeManage\ChangeManageRepository::class);
        $this->app->bind(\App\Repositories\Catalog\CatalogRepositoryInterface::class, \App\Repositories\Catalog\CatalogRepository::class);
        $this->app->bind(\App\Repositories\User3Easy\User3EasyServiceRepositoryInterface::class, \App\Repositories\User3Easy\User3EasyServiceRepository::class);
        $this->app->bind(\App\Repositories\User3Easy\User3EasyRepositoryInterface::class, \App\Repositories\User3Easy\User3EasyRepository::class);
        $this->app->bind(\App\Repositories\Location\LocationRepositoryInterface::class, \App\Repositories\Location\LocationRepository::class);
        $this->app->bind(\App\Repositories\LogActivity\LogActivityRepositoryInterface::class, \App\Repositories\LogActivity\LogActivityRepository::class);
        //:end-bindings:
    }
}
