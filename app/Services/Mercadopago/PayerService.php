<?php


namespace App\Services\Mercadopago;

use Illuminate\Http\Request;

use MercadoPago\Payer;

class PayerService
{
    public $payer;
    protected $payerParams = [];
    public function __construct($request)
    {
        $this->payer = $this->setPayer($request);
        $this->payerParams = $request;
        return $this->payer;
    }

    /**
     * @param $request
     * @return Payer
     */
    private function setPayer($request): Payer
    {
        $payer = new Payer();
        $payer->email = $request->email;
        $payer->first_name = $request->name;
        $payer->last_name = $request->lastname;
        $payer->identification = array(
            "type" => $request->identification_type,
            "number" => $request->identification_number
        );

        return $payer;
    }

}