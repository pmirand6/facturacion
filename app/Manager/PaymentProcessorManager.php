<?php


namespace App\Manager;


use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Disbursements\DisbursementsRepositoryInterface;
use App\Repositories\Payment\PaymentRepositoryInterface;
use App\Repositories\Provider\ProviderRepository;
use App\Transformers\CustomerTransformer;
use App\Transformers\PaymentTransformer;
use Illuminate\Support\Facades\Log;


class PaymentProcessorManager
{

    use PaymentTransformer;
    use CustomerTransformer;

    /**
     * @var PaymentRepositoryInterface
     */
    private $payment;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customer;
    /**
     * @var ProviderRepository
     */
    private $provider;
    private $disbursement;

    public function __construct(
        PaymentRepositoryInterface $payment,
        CustomerRepositoryInterface $customer,
        DisbursementsRepositoryInterface $disbursementsRepository
    )
    {
        $this->payment = $payment;
        $this->customer = $customer;
        $this->disbursement = $disbursementsRepository;
    }

    public function savePaymentParameters($request)
    {
        $customer = $this->storeCustomer($request);

        try {
            $this->payment->store($this->transformPaymentRequest($request), $customer->get('customer_id'));
        } catch (\Exception $e) {
            Log::error(json_encode([
                'error' => true,
                'response' => $e->getMessage(),
                'method' => __METHOD__,
                'params' => [
                    'customer' => $customer,
                    'request' => $request
                ]
            ]));
            return response()->json([
                'error' => true,
                'status' => "error",
                'message' => $e->getMessage(),
                'method' => __METHOD__,
                'params' => [
                    'customer' => $customer,
                    'request' => $request
                ]
            ]);
        }
    }

    /**
     * @param $request
     * @return mixed
     */
    private function storeCustomer($request)
    {
        try {
            return $this->customer->storeFromPayment($this->transformCustomerRequest($request));
        } catch (\Exception $e) {
            Log::error(json_encode([
                'error' => true,
                'response' => $e->getMessage(),
                'method' => __METHOD__,
                'params' => [
                    $request
                ]
            ]));
            return response()->json([
                'error' => true,
                'status' => "error",
                'message' => $e->getMessage(),
                'method' => __METHOD__,
                'params' => [
                    $request
                ]
            ]);
        }

    }

    /**
     * @param $request
     */
    public function saveDisbursement($request)
    {
        try {
            $this->disbursement->store($request);
        } catch (\Exception $e) {
            Log::error(json_encode([
                'error' => true,
                'response' => $e->getMessage(),
                'method' => __METHOD__,
                'params' => [
                    $request
                ]
            ]));
            return response()->json([
                'error' => true,
                'status' => "error",
                'message' => $e->getMessage(),
                'method' => __METHOD__,
                'params' => [
                    $request
                ]
            ]);
        }
    }
}