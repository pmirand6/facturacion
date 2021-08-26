<?php


namespace App\Repositories\Disbursements;


use App\Models\Disbursement;
use App\Models\Provider;
use App\Repositories\Item\ItemRepository;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

class DisbursementsRepository implements DisbursementsRepositoryInterface
{

    public function store($request)
    {
        try {
            $customDisbursements = $request->get('requestPayment')[0]['custom_disbursements'];
            $disbursementsCollection = collect($request->get('advancedPayment')->disbursements);
            $disbursementRSN = $disbursementsCollection->where('collector_id', env('RSN_ID_MERCADOPAGO'));
            $purchaseOrder = $request->get('advancedPayment')->payments[0]->external_reference;
            $this->storeRsnDisbursement($purchaseOrder, $disbursementRSN);

            $disbursementsCollection->each(function ($disbursement, $key) use ($customDisbursements, $purchaseOrder) {
                if ($disbursement->collector_id != env('RSN_ID_MERCADOPAGO')) {
                    $customCommissions = $this->setCustomCommissions($customDisbursements, $disbursement->collector_id);
                    $providerId = $this->getByMPId($disbursement->collector_id);
                    $disbursement = Disbursement::create([
                        'provider_id' => $providerId,
                        'provider_id_marketplace' => $disbursement->collector_id,
                        'purchase_order' => $purchaseOrder,
                        'amount_total_disbursement' => $disbursement->amount,
                        'amount_application_fee' => $disbursement->application_fee,
                        'amount_application_net_fee' => $customCommissions['amount_application_net_fee'],
                        'amount_rsn_fee_net' => $customCommissions['amount_rsn_fee_net'],
                        'amount_alitaware_fee_net' => $customCommissions['amount_alitaware_fee_net'],
                        'amount_rsn_fee' => $customCommissions['amount_rsn_fee'],
                        'amount_alitaware_fee' => $customCommissions['amount_alitaware_fee']
                    ]);

                    (new ItemRepository())->updateByPoAndProviderId($purchaseOrder, $providerId, $disbursement->id);
                }
            });
        } catch (\Exception $e) {
            Log::error(json_encode([
                'method' => __METHOD__,
                'error' => $e->getMessage()
            ]));
        }


    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function show($request)
    {
        // TODO: Implement show() method.
    }

    private function getByMPId($collector_id): int
    {
        $provider = Provider::where('marketplace_id', $collector_id)->first();

        return $provider ? $provider->id : 0;
    }

    private function storeRsnDisbursement($purchaseOrder, $disbursement)
    {
        $data = collect($disbursement)->first();

        if ($data) {
            Disbursement::create([
                'provider_id' => $this->getByMPId($data->collector_id),
                'provider_id_marketplace' => $data->collector_id,
                'purchase_order' => $purchaseOrder,
                'amount_total_disbursement' => $data->amount,
                'amount_application_fee' => $data->application_fee,
                'amount_application_net_fee' => 0,
                'amount_rsn_fee_net' => 0,
                'amount_alitaware_fee_net' => 0,
                'amount_rsn_fee' => 0,
                'amount_alitaware_fee' => 0
            ]);
        }

    }

    private function setCustomCommissions($collection, $collector_id): array
    {
        $amounts = [];
        $collection->each(function ($item, $key) use (&$amounts, $collector_id) {
            if ($key == $collector_id) {
                $amounts = [
                    'amount_application_net_fee' => $item['amount_application_net_fee'],
                    'amount_rsn_fee_net' => $item['amount_rsn_fee_net'],
                    'amount_alitaware_fee_net' => $item['amount_alitaware_fee_net'],
                    'amount_rsn_fee' => $item['amount_rsn_fee'],
                    'amount_alitaware_fee' => $item['amount_alitaware_fee'],
                ];
            }
        });
        return $amounts;
    }
}