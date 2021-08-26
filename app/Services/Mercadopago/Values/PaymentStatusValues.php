<?php


namespace App\Services\Mercadopago\Values;


use App\Services\Mercadopago\Status\PaymentStatus\ApprovedPayment;
use App\Services\Mercadopago\Status\PaymentStatus\CancelledPayment;
use App\Services\Mercadopago\Status\PaymentStatus\ChargedBackPayment;
use App\Services\Mercadopago\Status\PaymentStatus\FatalErrorPayment;
use App\Services\Mercadopago\Status\PaymentStatus\PartiallyRefundedPayment;
use App\Services\Mercadopago\Status\PaymentStatus\PendingPayment;
use App\Services\Mercadopago\Status\PaymentStatus\RefundedPayment;
use App\Services\Mercadopago\Status\PaymentStatus\RejectedPayment;

class PaymentStatusValues
{
    public static function strategy($status): string
    {
        switch ($status) {
            case 'approved':
                return ApprovedPayment::class;
            case 'rejected':
                return RejectedPayment::class;
            default:
                return FatalErrorPayment::class;
        }
    }


}