<?php


namespace App\Transformers;


trait CustomerTransformer
{
    public function transformCustomerRequest($request): array
    {
        $payerRequest = collect($request->get('requestPayment')->get('payer'));

        return [
            'customer_email' => $payerRequest->get('email'),
            'identification_type' => $payerRequest->get('identification_type'),
            'identification_number' => $payerRequest->get('identification_number'),
            'first_name' => $payerRequest->get('name'),
            'last_name' => $payerRequest->get('lastname'),
            'customer_address' => $payerRequest->get('address')['customer_address'],
            'zip_code' => $payerRequest->get('address')['zip_code'],
            'street_name' => $payerRequest->get('address')['street_name'],
            'street_number' => $payerRequest->get('address')['street_number'],
//            'country_code' => $payerRequest->get('country_code'),
//            'state_code' => $payerRequest->get('state_code'),
//            'erp_id' => $payerRequest->get('erp_id'),
//            'locality_code' => $payerRequest->get('locality_code'),
        ];
    }

}