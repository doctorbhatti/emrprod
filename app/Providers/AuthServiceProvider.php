<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model'           => 'App\Policies\ModelPolicy',
        'App\Models\Patient'         => 'App\Policies\PatientPolicy',
        'App\Models\Drug'            => 'App\Policies\DrugPolicy',
        'App\Models\DrugType'        => 'App\Policies\DrugTypePolicy',
        'App\Models\Stock'           => 'App\Policies\StockPolicy',
        'App\Models\Dosage'          => 'App\Policies\DosagePolicy',
        'App\Models\DosageFrequency' => 'App\Policies\DosageFrequencyPolicy',
        'App\Models\DosagePeriod'    => 'App\Policies\DosagePeriodPolicy',
        'App\Models\Prescription'    => 'App\Policies\PrescriptionPolicy',
        'App\Models\Queue'           => 'App\Policies\QueuePolicy',
        'App\Models\User'            => 'App\Policies\UserPolicy',
        'App\Models\Payment'         => 'App\Policies\PaymentPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot(Gate $gate)
    {
        $this->registerPolicies();

        // You can define additional custom Gate abilities here
        // Example:
        // $gate->define('custom-ability', function ($user) {
        //     return $user->isAdmin();
        // });
    }
}
