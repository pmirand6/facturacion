<?php

namespace App\Jobs;

use App\Mail\ProviderSummary;
use App\Models\Provider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ProviderInvoice extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $providers = Provider::with(['disbursements' => function ($query) {
            $query->whereMonth('created_at', '=', Carbon::now()->subMonth()->month);
        }])
            ->where('marketplace_id', '<>', env('RSN_ID_MERCADOPAGO'))
            ->get()
            ->toArray();

        foreach ($providers as $provider) {
            if (empty($provider['disbursements'])) {
                continue;
            }

            //$toMail = 'guille.gabriel.ojeda@gmail.com';
            $toMail = $provider["provider_email"];
            Mail::to($toMail)->send(new ProviderSummary($provider));
        }
    }
}
