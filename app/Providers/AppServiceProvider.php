<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Interfaces\CompanyRepositoryInterface;
use App\Domain\Interfaces\EmployeeRepositoryInterface;
use App\Domain\Interfaces\UnitOfWorkInterface;
use App\Infrastructure\Repositories\CompanyRepository;
use App\Infrastructure\Repositories\EmployeeRepository;
use App\Infrastructure\UnitOfWork\UnitOfWork;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(UnitOfWorkInterface::class, UnitOfWork::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Log::info('Inicio de la aplicación');
    }
}
