<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RsnBilling extends Mailable
{
    use Queueable, SerializesModels;

    public $shippingBilling;

    /**
     * Create a new message instance.
     *
     * @param $shippingBilling
     */
    public function __construct($shippingBilling)
    {
        $this->shippingBilling = $shippingBilling;
    }

    /**
     * Build the message.
     *
     * @return string
     */
    public function build()
    {
        try {
            return $this->from(env('MAIL_FROM_ADDRESS'))
                ->subject('Datos para factura de envío a domicilio - Compra: ' . $this->shippingBilling["purchase_order"] )
                ->view('emails.billing.rsn',
                    [
                        'shippingBilling' => $this->shippingBilling
                    ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
