<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share clinic name and logo globally with all views
        View::composer('*', function ($view) {
            // Fetch the clinic associated with the logged-in user
            $clinic = auth()->user()->clinic ?? null;

            // Get clinic name or set a default
            $clinicName = $clinic->name ?? 'Default Clinic Name';

            // Get the clinic's logo or use a default logo if none is set
            $currentLogo = $clinic->logo ?? 'FrontTheme/images/logo.png';

            // Share both clinicName and logo with all views
            $view->with([
                'clinicName' => $clinicName,
                'currentLogo' => $currentLogo, // This will be used in the view
            ]);
        });



        // Force HTTPS in production environment
        if ($this->app->environment() === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
