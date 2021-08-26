<?php


namespace App\Services\Mercadopago\Fee;


trait FeeCalculator
{
    //FIXME TRAER VALORES DESDE TIENDA
    public function calculateFee($amount): float
    {
        $fee = $amount * config('fee_config.FEE_PERCENT');
        $fee = $fee * config('fee_config.IVA');

        return (float)number_format((float)$fee, 2, '.', '');
    }
}