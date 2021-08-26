<?php


namespace App\Transformers;


trait PaymentTransformer
{
    public function transformPaymentRequest($request): array
    {

        return [
            'advancedPaymentId' => $request->get('advancedPayment')->id,
            'paymentId' => $request->get('advancedPayment')->payments[0]->id,
            'paymentStatus' => $request->get('advancedPayment')->payments[0]->status,
            'transactionAmount' => $request->get('advancedPayment')->payments[0]->transaction_amount,
            'purchaseOrder' => $request->get('advancedPayment')->payments[0]->external_reference,
            'totalAmountDelivery' => (isset($request['shippingBilling']['order']['total'])) ? $request['shippingBilling']['order']['total'] : 0,
            'totalNetAmountDelivery' => (isset($request['shippingBilling']['order']['total_net'])) ? $request['shippingBilling']['order']['total_net'] : 0,
            'items' => $request->get('requestPayment')->get('items')// realizar foreach
        ];
    }
}