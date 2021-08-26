<?php

namespace App\Console\Commands;

use App\Mail\ProviderSummary;
use App\Mail\ProviderSummaryToAlitaware;
use App\Mail\ProviderSummaryToRsn;
use App\Models\BillingParameter;
use App\Models\Disbursement;
use App\Models\Notifications;
use App\Models\Provider;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvoiceProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice_provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envío de email para generar factura a productor';
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
        //Get RSN admin email from billing parameters
        $email_admin_rsn = BillingParameter::first()->email_admin_rsn;

        $arrayDate = [
            'dateGenerated' => \Carbon\Carbon::now()->format('d/m/Y'),
            'dateFrom' => Carbon::now()->startOfMonth()->subMonth()->format('d/m/Y'),
            'dateTo' => Carbon::now()->subMonth()->endOfMonth()->format('d/m/Y')
        ];

        $providers = $this->getProviderDisbursements();

        foreach ($providers as $provider) {
            if (empty($provider['disbursements'])) {
                continue;
            }
            $toMail = $provider["provider_email"];

            $this->invoiceResumeProvider($toMail, $provider, $arrayDate);
            $this->invoiceRsn($email_admin_rsn, $provider, $arrayDate);
            $this->invoiceAlitaware($email_admin_rsn, $provider, $arrayDate);
            $this->saveNotification($provider);

        }
    }

    private function saveNotification($provider)
    {
        foreach ($provider['disbursements'] as $disbursement) {
            Notifications::updateOrCreate(
                ['type' => 'provider'],
                ['last_id' => $disbursement["id"]]
            );
            Disbursement::find($disbursement['id'])->update(['provider_notified' => 1]);
        }
    }

    /**
     * @param $toMail
     * @param $provider
     * @param array $arrayDate
     */
    private function invoiceResumeProvider($toMail, $provider, array $arrayDate): void
    {
        Log::info("Sending resume Invoice to {$toMail} ", [
            'payload' => [
                'provider_id' => $provider["id"],
                'bcc' => env('INVOICE_BCC_MAIL'),
                'method' => __METHOD__
            ]
        ]);
        try {
            Mail::to($toMail)
                ->bcc(env('INVOICE_BCC_MAIL'))
                ->send(new ProviderSummary($provider, $arrayDate));
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
     * @param $provider
     * @param array $arrayDate
     * @param string $email_admin_rsn
     */
    private function invoiceRsn(string $email_admin_rsn, $provider, array $arrayDate): void
    {
        Log::info("Sending invoice for RSN ", [
            'payload' => [
                'rsn_admin' => $email_admin_rsn,
                'bcc' => env('INVOICE_BCC_MAIL'),
                'method' => __METHOD__
            ]
        ]);
        try {
            Mail::to($email_admin_rsn)
                ->bcc(env('INVOICE_BCC_MAIL'))
                ->send(new ProviderSummaryToAlitaware($provider, $arrayDate)); //FIXME mail de admin alitaware
        } catch (Exception $e) {
            Log::error('Error Sending Invoice for RSN', [
                'payload' => [
                    'method' => __METHOD__,
                    'error' => $e->getMessage(),
                    'rsn_admin' => env('MAIL_RSN_CLIENT_TEST'),
                    'bcc' => env('INVOICE_CC_MAIL')
                ]
            ]);
        }

    }

    /**
     * @param $email_admin_rsn
     * @param $provider
     * @param array $arrayDate
     */
    private function invoiceAlitaware($email_admin_rsn, $provider, array $arrayDate): void
    {
        Log::info("Sending invoice for Alitaware ", [
            'payload' => [
                'alitaware_admin' => $email_admin_rsn,
                'bcc' => env('INVOICE_BCC_MAIL'),
                'method' => __METHOD__
            ]
        ]);
        try {
            Mail::to($email_admin_rsn)
                ->bcc(env('INVOICE_BCC_MAIL'))
                ->send(new ProviderSummaryToRsn($provider, $arrayDate));
        } catch (Exception $e) {
            Log::error('Error Sending Invoice for Alitaware', [
                'payload' => [
                    'method' => __METHOD__,
                    'error' => $e->getMessage(),
                    'alitaware_admin' => $email_admin_rsn,
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
            $query->where('provider_notified', '=', 0);
        }])
            ->where('marketplace_id', '<>', env('RSN_ID_MERCADOPAGO'))
            ->get()
            ->toArray();
    }
}
