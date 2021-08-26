<?php

namespace App\Providers;

use App\Manager\PaymentProcessorManager;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Disbursements\DisbursementsRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Provider\ProviderRepository;
use App\Services\Mercadopago\AdvancedPaymentService;
use App\Services\Mercadopago\DisbursementsService;
use App\Services\Mercadopago\PayerService;
use App\Services\Mercadopago\PaymentService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentService::class, function ($app) {
            return new PaymentProcessorManager(
                app(PaymentRepository::class),
                app(CustomerRepository::class),
                app(DisbursementsRepository::class)
            );
        });
    }
}
