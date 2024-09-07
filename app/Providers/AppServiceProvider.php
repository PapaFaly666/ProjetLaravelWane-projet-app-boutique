<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\User;
use App\Observers\ClientObserver;
use App\Observers\UserObserver;
use App\Repository\ClientRepositoryImp;
use App\Services\ClientServiceImpl;
use App\Services\CloudinaryService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('client_repository',function($app){
            return new ClientRepositoryImp();
        });

        $this->app->singleton('client_service', function($app){
            // Assurez-vous de passer l'instance de CloudinaryService au ClientServiceImpl
            return new ClientServiceImpl($app->make(CloudinaryService::class));
        });

        // Enregistrez CloudinaryService ici
        $this->app->singleton(CloudinaryService::class, function($app){
            return new CloudinaryService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Client::observe(ClientObserver::class);
    }
}
