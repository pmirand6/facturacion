<?php

namespace App\Console;

use App\Console\Commands\AdminTestRegister;
use App\Console\Commands\createRSNProvider;
use App\Console\Commands\InvoiceCustomer;
use App\Console\Commands\InvoiceProvider;
use App\Console\Commands\InvoiceProviderFE;
use App\Jobs\ProviderInvoice;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AdminTestRegister::class,
        InvoiceCustomer::class,
        InvoiceProvider::class,
        createRSNProvider::class,
        InvoiceProviderFE::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(ProviderInvoice::class)->monthlyOn(1, '03:00');
    }
}
