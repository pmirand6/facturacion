<?php


namespace App\Providers;


use App\Repositories\Auth0\Auth0Repository;
use App\Repositories\Auth0\Auth0RepositoryInterface;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Disbursements\DisbursementsRepository;
use App\Repositories\Disbursements\DisbursementsRepositoryInterface;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Payment\PaymentRepositoryInterface;
use App\Repositories\BillingParameters\BillingParametersRepository;
use App\Repositories\BillingParameters\BillingParametersRepositoryInterface;
use App\Repositories\Provider\ProviderRepository;
use App\Repositories\Provider\ProviderRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(Auth0RepositoryInterface::class, Auth0Repository::class);

        $this->app->bind(ProviderRepositoryInterface::class, ProviderRepository::class);

        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);

        $this->app->bind(BillingParametersRepositoryInterface::class, BillingParametersRepository::class);

        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);

        $this->app->bind(DisbursementsRepositoryInterface::class, DisbursementsRepository::class);

    }

}