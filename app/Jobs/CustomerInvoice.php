<?php

namespace App\Jobs;

use App\Models\BillingParameter;
use App\Models\Payment;

class CustomerInvoice extends Job
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

        $payment = Payment::with(['items' => function ($query) {
            $query->where('delivery_type', 'delivery');
        }])->with('customer');

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

            Mail::to($email_admin_rsn)->send(new RsnBilling($item));
            $this->saveNotification($item['id']);
            Payment::find($item['id'])->update(['customer_notified' => 1]);
        }
    }

}
