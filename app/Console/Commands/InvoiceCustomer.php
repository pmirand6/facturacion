<?php

namespace App\Console\Commands;

use App\Mail\RsnBilling;
use App\Models\BillingParameter;
use App\Models\Notifications;
use App\Models\Payment;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvoiceCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice_customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envío de email para generar factura a customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $job = $this->getLastNotificationId();

        $matchThese = ['customer_notified' => 0, 'payment_status' => 'approved'];

        $payment = Payment::where($matchThese)->with(['items' => function ($query) {
            $query->where('delivery_type', 'delivery');
        }])->with('customer');

        if ($job) {
            $payment = $payment->where('payments.id', '>', $job->last_id);
        }

        $payment = $payment->get()->toArray();
        //Get RSN admin email from billing parameters
        $email_admin_rsn = BillingParameter::first()->email_admin_rsn;

        foreach ($payment as $item) {
            if (empty($item['items'])) {
                /**
                 * FIXME SE DEBERIA AGREGAR UNA COLUMNA PARA CONTROLAR SI EL PAYMENT DEBE SER INFORMADO PARA NO VERIFICAR EL ARRAY DE ITEMS
                 */
                $this->saveNotification($item['id']);
                Payment::find($item['id'])->update(['customer_notified' => 1]);
                continue;
            }
            Log::info("Sending invoice customer to {$email_admin_rsn} ", [
                'payload' => [
                    'email' => $email_admin_rsn,
                    'bcc' => env('INVOICE_BCC_MAIL'),
                    'item' => $item,
                    'method' => __METHOD__
                ]
            ]);
            try {
                Mail::to($email_admin_rsn)
                    ->bcc(env('INVOICE_BCC_MAIL'))
                    ->send(new RsnBilling($item));
                $this->saveNotification($item['id']);
            } catch (Exception $e) {
                Log::error('Error Sending Invoice Customer to RSN Admin', [
                    'payload' => [
                        'method' => __METHOD__,
                        'error' => $e->getMessage(),
                        'customerEmail' => $email_admin_rsn,
                        'bcc' => env('INVOICE_CC_MAIL')
                    ]
                ]);
            }
            Payment::find($item['id'])->update(['customer_notified' => 1]);
        }
    }

    private function getLastNotificationId()
    {
        return Notifications::where('type', 'customer')->latest('created_at')->first();
    }

    private function saveNotification($paymentId)
    {
        Notifications::updateOrCreate(
            ['type' => 'customer'],
            ['last_id' => $paymentId]
        );
    }
}
