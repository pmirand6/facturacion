<?php


namespace App\Repositories\Customer;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;


class CustomerRepository implements CustomerRepositoryInterface
{

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function show($request)
    {
        // TODO: Implement show() method.
    }

    public function storeFromPayment($request): Collection
    {
        $customerRequest = collect($request);

        try {

            //FIXME CUANDO EL CUSTOMER NO EXISTA EN BASE, SE DEBE CREAR ANTERIORMENTE EN EL ERP

            $customer = Customer::firstOrCreate(
                ['customer_email' => $customerRequest->get('customer_email')],
                [
                    'identification_type' => $customerRequest->get('identification_type'),
                    'identification_number' => $customerRequest->get('identification_number'),
                    'first_name' => $customerRequest->get('first_name'),
                    'last_name' => $customerRequest->get('last_name'),
                    'customer_address' => $customerRequest->get('customer_address'),
                    'zip_code' => $customerRequest->get('zip_code'),
                    'street_name' => $customerRequest->get('street_name'),
                    'street_number' => $customerRequest->get('street_number'),
                    'country_code' => 'test',
                    'state_code' => 'test',
                    'erp_id' => (string)rand(),
                    'locality_code' => 'test',
                ]
            );

            Log::info(json_encode([
                'method' => __METHOD__,
                'customer_id' => $customer->id
            ]));

            return collect([
                'error' => false,
                'customer_id' => $customer->id
            ]);
        } catch (\Exception $e) {
            Log::error(json_encode([
                'method' => __METHOD__,
                'error' => $e->getMessage()
            ]));
            return collect([
                'error' => true
            ]);


        }
    }

    public function update($request)
    {
        // TODO: Implement update() method.
    }

    public function store($request)
    {
        // TODO: Implement store() method.
    }
}