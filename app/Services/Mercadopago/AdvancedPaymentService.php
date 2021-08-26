<?php


namespace App\Services\Mercadopago;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\AdvancedPayments\AdvancedPayment;
use MercadoPago\SDK;
use App\Services\Mercadopago\Status\StatusHandler;

class AdvancedPaymentService
{
    use StatusHandler;

    protected $paymentService = [];
    protected $payerService = [];
    protected $disbursementsService;
    protected $shippingBilling = [];
    private $response = [];
    protected $params;
    private $requestPayment;

    public function __construct($request)
    {
        $this->requestPayment = collect($request);
        $this->params = json_decode(collect($request)->toJson());
        $this->payerService = (new PayerService($this->params->payer))->payer;
        $this->paymentService[] = (new PaymentService($this->params->payment))->payment;
        $this->disbursementsService = new DisbursementsService($this->params);
        $this->shippingBilling = $this->setShippingBilling($this->payerService, $this->disbursementsService->getShippingBilling());
        $this->response = $this->setAdvancedPayment();

    }

    private function setAdvancedPayment()
    {
        $advanced = new AdvancedPayment();

        $disbursementsWithoutCustom = array_filter($this->disbursementsService->disbursements, function ($key) {
            return is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);


        $advanced->payer = $this->payerService;
        $advanced->payments = $this->paymentService;
        $advanced->application_id = env('APPLICATION_ID');
        $advanced->disbursements = $disbursementsWithoutCustom;
        $advanced->binary_mode = true;
        $this->logTransactionParams();

        $advanced->save();

        $this->requestPayment [] = collect($this->disbursementsService->disbursements);


        return $advanced;
    }

    public function getResponse()
    {

        return $this->statusHandler($this->response, $this->requestPayment, $this->shippingBilling);
    }

    private function setShippingBilling($payer, $shippingBilling)
    {
        if ($shippingBilling) {
            $shippingBilling["payer"]["name"] = $payer->first_name;
            $shippingBilling["payer"]["lastname"] = $payer->last_name;
            $shippingBilling["payer"]["email"] = $payer->email;
            $shippingBilling["payer"]["identification"] = $payer->identification;
        } else {
            $shippingBilling = null;
        }

        return $shippingBilling;
    }

    private function logTransactionParams()
    {
        Log::info(json_encode([
            'payerResult' => $this->payerService->toArray(),
            'paymentResult' => $this->paymentService[0]->toArray(),
            'disbursementsResult' => $this->disbursementsService->disbursements,
            'params' => $this->params->items,
        ]));
    }

}