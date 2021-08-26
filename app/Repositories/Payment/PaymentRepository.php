<?php


namespace App\Repositories\Payment;


use App\Models\Item;
use App\Models\Payment;
use App\Models\Provider;
use App\Repositories\Provider\ProviderRepository;
use Illuminate\Support\Facades\Log;

class PaymentRepository implements PaymentRepositoryInterface
{

    public function store($request, $customer_id)
    {
        try {
            $payment = Payment::create([
                'purchase_order' => $request['purchaseOrder'],
                'marketplace_payment_code' => $request['paymentId'],
                'marketplace_advanced_payment_code' => $request['advancedPaymentId'],
                'payment_status' => $request['paymentStatus'],
                'total_amount_delivery' => $request['totalAmountDelivery'],
                'total_net_amount_delivery' => $request['totalNetAmountDelivery'],
                'customer_id' => $customer_id,
            ]);
            foreach ($request['items'] as $item) {
                //FIXME pasar a un item repository
                Item::create([
                    'payment_status' => $request['paymentStatus'],
                    'item_code' => $item['item_code'],
                    'item_name' => $item['item_name'],
                    'item_quantity' => $item['quantity'],
                    'delivery_type' => $item['delivery_type'],
                    'item_unit_price' => $item['unit_price'],
                    'item_subtotal' => $item['subtotal'],
                    'delivery_cost' => (array_key_exists('delivery_cost', $item)) ? $item['delivery_cost'] : 0,
                    'delivery_cost_iva' => (array_key_exists('iva_delivery_cost', $item)) ? $item['iva_delivery_cost'] : 0,
                    'delivery_cost_net' => (array_key_exists('net_delivery_cost', $item)) ? $item['net_delivery_cost'] : 0,
                    'provider_id' => $this->getProviderByMarketPlaceId($item['provider']['id_marketplace']),
                    'payment_id' => $payment->id,
                    'purchase_order' => $request['purchaseOrder']
                ]);
            }
        } catch (\Exception $e) {
            Log::error(json_encode([
                'method' => __METHOD__,
                'error' => $e->getMessage()
            ]));
        }
    }

    public function show($request)
    {
        // TODO: Implement show() method.
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    private function getProviderByMarketPlaceId($id_marketplace): int
    {
        $providerRepository = Provider::where('marketplace_id', $id_marketplace)->first();

        return $providerRepository ? $providerRepository->id : 0;

    }
}