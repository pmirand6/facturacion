<?php


namespace App\Manager;


use App\Services\Mercadopago\AdvancedPaymentService;
use App\Services\Mercadopago\PayerService;
use App\Services\Mercadopago\PaymentService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\AdvancedPayments\AdvancedPayment;

class MercadopagoManager
{
    private $payment;
    private $payer;
    private $advancedPayment;
    private $request;

    public function __construct(Request $request)
    {

        try {
            $this->payment = new PaymentService($request);
            $this->payer = new PayerService($request);
            $this->request = $request;
        } catch (\Exception $e) {
            Log::error(json_encode([
                'class' => __METHOD__,
                'error' => $e->getMessage()
            ]));
        }


    }
    
    public function enrollVendor()
    {
        
    }

    public function feeCalculation()
    {
        
    }

    public function splitDisbursements()
    {
        
    }

    public function stateContext()
    {
        
    }

    public function createPayment()
    {
        try {
            $advancedPayment = new AdvancedPaymentService($this->request, $this->payer, $this->payment);
        } catch (\Exception $e) {
            Log::error(json_encode([
                'class' => __METHOD__,
                'error' => $e->getMessage()
            ]));
        }
    }
    
    
}