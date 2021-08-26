<?php


namespace App\Services\Mercadopago;


use App\Services\Mercadopago\Fee\FeeCalculator;
use App\Services\Mercadopago\Fee\FeeMarketPlaceOwnersCalculator;
use App\Services\Mercadopago\Fee\FeeNetCalculator;

class DisbursementsService
{
    use FeeCalculator;
    use FeeNetCalculator;
    use FeeMarketPlaceOwnersCalculator;

    public $disbursements = [];

    private $deliveryCost;

    private $shippingBilling = [];

    public function __construct($request)
    {
        $this->disbursements = $this->setDisbursements($request);
        $this->shippingBilling = $this->setShippingBilling($request);
    }

    private function setDisbursements($request): array
    {

        $items = $request->items;
        $collection = collect($items);
        $splitDisbursement = [];

        $splitProviders = $this->setArrayProvider($collection, $request->payment->external_reference);
        $splitCustomDisbursements = $this->setArrayCustomComissions($collection);

        foreach ($splitProviders as $splitProvider) {
            $splitDisbursement[] = $splitProvider;
        }

        foreach ($collection as $item) {
            if ($item->delivery_type == 'delivery') {
                $this->deliveryCost += $item->delivery_cost;
            }
        }

        if ($this->deliveryCost > 0) {
            $splitDisbursement[] = $this->setRSNDisbursement($request->payment->external_reference);
        }

        $splitDisbursement['custom_disbursements'] = $splitCustomDisbursements;


        return $splitDisbursement;
    }

    private function setArrayProvider($collection, $purchaseOrder)
    {
        return $collection->groupBy('provider.id_marketplace')->map(function ($row) use ($purchaseOrder) {
            return [
                'amount' => $row->sum('subtotal'),
                "external_reference" => $purchaseOrder,
                "collector_id" => $row[0]->provider->id_marketplace,
                "application_fee" => $this->calculateFee($row->sum('subtotal')),
                "money_release_days" => 30
            ];
        });

    }

    private function setArrayCustomComissions($collection)
    {
        return $collection->groupBy('provider.id_marketplace')->map(function ($row) {
            return [
                "amount_application_net_fee" => $this->calculateNetFee($row->sum('subtotal')), // sin el 21 %
                "amount_rsn_fee_net" => $this->RSNNetFeeCalculate($this->calculateNetFee($row->sum('subtotal'))), // sin el 21 %
                "amount_alitaware_fee_net" => $this->AlitawareNetFeeCalculate($this->calculateNetFee($row->sum('subtotal'))), // sin el 21 %
                "amount_rsn_fee" => $this->RSNNetFeeCalculate($this->calculateFee($row->sum('subtotal'))), // con el 21 %
                "amount_alitaware_fee" => $this->AlitawareNetFeeCalculate($this->calculateFee($row->sum('subtotal'))), // con el 21 %
            ];
        });

    }


    private function setRSNDisbursement($purchaseOrder): array
    {
        return $rsnArray = [
            "amount" =>  (float)number_format((float)$this->deliveryCost, 2, '.', ''),
            "external_reference" => $purchaseOrder,
            "collector_id" => env('RSN_ID_MERCADOPAGO'),
            "application_fee" => 0,
            "money_release_days" => 30
        ];
    }

    public function getShippingBilling(): array
    {
        return $this->shippingBilling;
    }

    private function setShippingBilling($request): array
    {
        $items = $request->items;
        $collection = collect($items);
        $arrayShipping = $collection->groupBy('provider.id_provider');

        $arrayShippingBilling = [];
        $cost = 0;
        $cost_net = 0;
        foreach ($arrayShipping as $item => $key) {
            foreach ($key as $b) {
                if ($b->delivery_type == 'delivery') {
                    $arrayShippingBilling["items"][$b->item_code]["name"] = $b->item_name;
                    $arrayShippingBilling["items"][$b->item_code]["quantity"] = $b->quantity;
                    $arrayShippingBilling["items"][$b->item_code]["delivery_cost"] = $b->delivery_cost;
                    $arrayShippingBilling["items"][$b->item_code]["net_delivery_cost"] = $b->delivery_cost;
                    $cost += $b->delivery_cost;
                    $cost_net += $b->net_delivery_cost;
                }
            }
        }
        if ($cost) {
            $arrayShippingBilling["order"]["external_reference"] = $request->payment->external_reference;
            $arrayShippingBilling["order"]["total"] = $cost;
            $arrayShippingBilling["order"]["total_net"] = $cost_net;
        }

        return $arrayShippingBilling;
    }

}