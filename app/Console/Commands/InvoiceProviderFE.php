<?php

namespace App\Console\Commands;

use App\Mail\ProviderSummaryFE;
use App\Models\Provider;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvoiceProviderFE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice_provider_fe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envío de email de fe de erratas para productor';
    /**
     * @var Provider
     */
    private $provider;

    /**
     * Create a new command instance.
     *
     * @param Provider $provider
     */
    public function __construct(Provider $provider)
    {
        parent::__construct();
        $this->provider = $provider;
    }

    /**
     * Método que envía sólo los disbursements correspondientes al mes anterior
     *
     * @return mixed
     */
    public function handle()
    {
        $arrayDate = [
            'dateGenerated' => \Carbon\Carbon::now()->format('d/m/Y'),
            'dateFrom' => Carbon::now()->startOfMonth()->subMonth()->format('d/m/Y'),
            'dateTo' => Carbon::now()->subMonth()->endOfMonth()->format('d/m/Y')
        ];

        $providers = $this->getProviderDisbursements();

        foreach ($providers as $provider) {
            Log::info('provider', [
               'disbursements' => $provider
            ]);

            if (empty($provider['disbursements'])) {
                continue;
            }
            $toMail = $provider["provider_email"];

            $this->invoiceResumeProvider($toMail, $provider, $arrayDate);
        }
    }

    /**
     * @param $toMail
     * @param $provider
     * @param array $arrayDate
     */
    private function invoiceResumeProvider($toMail, $provider, array $arrayDate): void
    {
        Log::info("Sending FE Invoice to {$toMail} ", [
            'payload' => [
                'provider_id' => $provider["id"],
                'bcc' => env('INVOICE_BCC_MAIL'),
                'method' => __METHOD__
            ]
        ]);
        try {
            Mail::to($toMail)
                ->bcc(env('INVOICE_BCC_MAIL'))
                ->send(new ProviderSummaryFE($provider, $arrayDate));
        } catch (Exception $e) {
            Log::error('Error Sending Invoice Resume to Provider', [
                'payload' => [
                    'method' => __METHOD__,
                    'error' => $e->getMessage(),
                    'providerMail' => $toMail,
                    'bcc' => env('INVOICE_CC_MAIL')
                ]
            ]);
        }

    }

    /**
     * @return array
     */
    private function getProviderDisbursements(): array
    {
        return $this->provider->with(['disbursements' => function ($query) {
            $query->whereHas('payment', function ($query){
               $query->where('payment_status', 'approved');
            });
            $query->whereMonth('created_at', '=', Carbon::now()->subMonth()->month);
        }])
            ->where('marketplace_id', '<>', env('RSN_ID_MERCADOPAGO'))
            ->get()
            ->toArray();
    }
}
