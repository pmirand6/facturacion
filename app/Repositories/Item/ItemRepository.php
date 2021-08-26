<?php


namespace App\Repositories\Item;


use App\Models\Item;
use Illuminate\Support\Facades\Log;

class ItemRepository implements ItemRepositoryInterface
{

    public function store($request)
    {
        // TODO: Implement store() method.
    }

    public function show($request)
    {
        // TODO: Implement show() method.
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function updateByPoAndProviderId($purchaseOrder, $providerId, $disbursementId)
    {
        try {
            $items = Item::where([
                    ['purchase_order', '=', $purchaseOrder],
                    ['provider_id', '=', $providerId]
                ])->get();

            foreach ($items as $item) {
                $item->disbursement_id = $disbursementId;
                $item->save();
            }

        } catch (\Exception $e) {
            Log::error(json_encode([
                'method' => __METHOD__,
                'error' => $e->getMessage()
            ]));
        }
    }
}