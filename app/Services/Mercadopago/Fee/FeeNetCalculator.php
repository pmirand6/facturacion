<?php


namespace App\Services\Mercadopago\Fee;


trait FeeNetCalculator
{
    //FIXME TRAER VALORES DESDE TIENDA
    public function calculateNetFee($amount): float
    {
        $fee = $amount * config('fee_config.FEE_PERCENT');
        return (float)number_format((float)$fee, 2, '.', '');
    }

}