<?php


namespace App\Services\Mercadopago\Fee;


use App\Models\BillingParameter;

trait FeeMarketPlaceOwnersCalculator
{
    public function RSNNetFeeCalculate($netAmount): float
    {
        $commission_percent_rsn = BillingParameter::first()->commission_percent_rsn;
        return $this->getAmountPercent($netAmount, $commission_percent_rsn);
    }

    public function AlitawareNetFeeCalculate($netAmount): float
    {
        $commission_percent_alitaware = BillingParameter::first()->commission_percent_alitaware;
        return $this->getAmountPercent($netAmount, $commission_percent_alitaware);
    }

    private function getAmountPercent($netAmount, $percent): float
    {
        $amountPercent = $percent * ($netAmount / 100);
        return (float)number_format((float)$amountPercent, 2, '.', '');
    }


}