<?php


namespace App\Http\Controllers\Api\v1;


use App\Services\Mercadopago\AdvancedPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\AdvancedPayments\AdvancedPayment;
use MercadoPago\Item;
use MercadoPago\Payer;
use MercadoPago\Payment;
use MercadoPago\Preference;
use MercadoPago\SDK;

class MercadoPagoTestController extends Controller
{
    public function testCreatePreference(Request $request)
    {
        SDK::setAccessToken(env('ACCESS_TOKEN_PROD'));
        $preference = new Preference();
        $item = new Item();
        $item->title = $request->description;
        $item->quantity = $request->quantity;
        $item->unit_price = $request->price;

        $preference->items = array($item);

        $preference->back_urls = array(
            "success" => "https://facturaciondev.feriame.com/feedback",
            "failure" => "https://facturaciondev.feriame.com/feedback",
            "pending" => "https://facturaciondev.feriame.com/feedback",
        );
        $preference->auto_return = "approved";
        $preference->external_reference = "FERIAME-PO";
        $preference->statement_descriptor = "COMPRA FERIAME";

        $preference->save();

        $response = array(
            'id' => $preference->id,
        );

        echo json_encode($response);
    }

    public function paymentResult(Request $request)
    {
        dd($request->all());
    }
    public function testPayment(Request $request)
    {
        try {
            //SDK::setAccessToken("TEST-7874528410460727-011000-05667e388c430f99d53107fa74b1d094-699480411");
            SDK::setAccessToken(env('ACCESS_TOKEN_TEST'));

            return app(AdvancedPaymentService::class, ['request' => $request]);
        } catch (\Exception $e) {
            Log::error(json_encode([
                'class' => __METHOD__,
                'error' => $e->getMessage()
            ]));
        }
    }

    public function test()
    {


        // Agrega credenciales
        //Vendedor de prueba
        SDK::setAccessToken("TEST-7874528410460727-011000-05667e388c430f99d53107fa74b1d094-699480411");
        //Marketplace Admin
        //SDK::setAccessToken('TEST-1967821053256817-010921-05d2d3b08ef58ffe3c524e6c9626f1b6-84203274');

// Crea un objeto de preferencia
        //$preference = new Preference();

//// Crea un ítem en la preferencia
//        $item = new Item();
//
//        $item->title = 'Mi producto';
//        $item->quantity = 1;
//        $item->unit_price = 75;
//        $preference->items = array($item);
//        $preference->save();

        $preference = new Preference();

        $item = new Item();
        $item->title = "Blue shirt";
        $item->quantity = 1;
        $item->unit_price = 75;
        $preference->items = array($item);
        $preference->marketplace_fee = 2.0;
        $preference->external_reference = "TEST-7874528410460727-011000-05667e388c430f99d53107fa74b1d094-699480411";
        $preference->notification_url = "https://facturacion.feriame.com/api/v1/mercadopago-ipn";

        $preference->save();

        dd($preference->sandbox_init_point);

    }

    public function marketplace(Request $request)
    {
        Log::info(json_encode([
            'request' => $request->all()
        ]));

        //FIXME GENERAR EL REQUEST DE CURL PARA LAS CREDENCIALES DEL VENDEDOR

//        curl -X POST \
//    -H 'accept: application/json' \
//    -H 'content-type: application/x-www-form-urlencoded' \
//    'https://api.mercadopago.com/oauth/token' \
//    -d 'client_secret=ACCESS_TOKEN' \
//    -d 'grant_type=authorization_code' \
//    -d 'code=AUTHORIZATION_CODE' \
//    -d 'redirect_uri=REDIRECT_URI'
    }

    public function ipn_notification(Request $request)
    {
        Log::info(json_encode([
            'request' => $request->all()
        ]));
    }

    public function api_test(Request $request)
    {
        SDK::setAccessToken(env('ACCESS_TOKEN_TEST'));

        //$payment = new Payment();
//        $payment->transaction_amount = (float)$request->transactionAmount'];
//        $payment->token = $_POST['token'];
//        $payment->description = $_POST['description'];
//        $payment->installments = (int)$_POST['installments'];
//        $payment->payment_method_id = $_POST['paymentMethodId'];
//        $payment->issuer_id = (int)$_POST['issuer'];

        $payment = new Payment();
        $payment->transaction_amount = (float)$request->transactionAmount;
        $payment->token = $request->token;
        $payment->description = $request->description;
        $payment->installments = (int)$request->installments;
        $payment->payment_method_id = $request->paymentMethodId;
        $payment->issuer_id = (int)$request->issuer;
        $payment->processing_mode = "aggregator";
        $payment->capture = true;
        $payment->external_reference = "externalRef123";
        $payment->statement_descriptor = "WWW.MktSplitterMLBTEST.COM.BR";

        $payments = [
            $payment
        ];

        Log::info(json_encode([
            'request' => $request->all()
        ]));

        Log::info(json_encode([
            'payment_object' => $payments
        ]));


        $advanced = new AdvancedPayment();

        $advanced->application_id = "7874528410460727";
        $advanced->disbursements = [
            [
                "amount" => 439.50, // valor del producto que vendió
                "external_reference" => "ref-collector-1", // referencia
                "collector_id" => "699921267", // id de mercadopago
                "application_fee" => 60.50, // comision (pesos) del marketplace - Descuento (total - (10% + iva))
                "money_release_days" => 30, // cuando va a tener la plata
            ],
            [
                "amount" => 219.75,
                "external_reference" => "ref-collector-2",
                "collector_id" => "699480411",
                "application_fee" => 30.25,
                "money_release_days" => 30,
            ],
            [
                "amount" => 219.75,
                "external_reference" => "ref-collector-3",
                "collector_id" => "700141868",
                "application_fee" => 30.25,
                "money_release_days" => 30,
            ]
        ];

        //Ejemplo de disbursements:
//        $advanced->disbursements = [
//            [
//                "amount" => 100, // valor del producto que vendió
//                "external_reference" => "ref-collector-1", // referencia
//                "collector_id" => "699921267", // id de mercadopago
//
//                //Ejemplo de division de la comisión entre dos propietarios del sitio
//                "application_fee" => [
//                    "comision_total" => 60.50, // comision (pesos) del marketplace - Descuento (total - (10% + iva))
//                    "socio1" => 42.35
//                    "socio2" => 18.5
//                ],
//                "money_release_days" => 30, // cuando va a tener la plata
//            ],
//            [
//                "amount" => 87.10,
//                "external_reference" => "ref-collector-2",
//                "collector_id" => "699480411",
//                "application_fee" => 30.25,
//                "money_release_days" => 30,
//            ],
//            [
//                "amount" => 100,
//                "external_reference" => "ref-collector-3",
//                "collector_id" => "700141868",
//                "application_fee" => 30.25,
//                "money_release_days" => 30,
//            ],
//            //Disbursment de envio + desagregado por la comision de RSN
//            [
//                "amount" => 19.35 + 180,
//                "external_reference" => "ref-collector-3",
//                "collector_id" => "700141868",
//                "application_fee" => 0,
//                "money_release_days" => 30,
//            ],
//            [
//                "amount" => 19.35,
//                "external_reference" => "ref-collector-3",
//                "collector_id" => "700141868",
//                "application_fee" => 0,
//                "money_release_days" => 30,
//            ],
//        ];

        //$advanced->external_reference = "APP_USR-7874528410460727-011000-8e1f75eec4444f6aa29ef9efffa47b9f-699482715";


        $payer = new Payer();
        $payer->email = $request->email;
        $payer->identification = array(
            "type" => $request->docType,
            "number" => $request->docNumber
        );
        $advanced->payments = $payments;
        $advanced->payer = $payer;
        dd($payment->token);
        $advanced->save();

        dd($advanced);
    }


}