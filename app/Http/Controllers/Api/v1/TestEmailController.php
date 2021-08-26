<?php


namespace App\Http\Controllers\Api\v1;


use App\Mail\RsnBilling;
use App\Models\Disbursement;
use App\Models\Notifications;
use App\Models\Payment;
use App\Models\Provider;
use App\Resources\Payment\PaymentResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestEmailController extends Controller
{
    public function mail()
    {

        $providers = Provider::with(['disbursements' => function ($query) {
            $query->whereMonth('created_at', '=', Carbon::now()->subMonth()->month);
        }])
            ->where('marketplace_id', '<>', env('RSN_ID_MERCADOPAGO'))
            ->get()
            ->toArray();


        foreach ($providers as $provider) {
            if(empty($provider['disbursements'])){
               continue;
            }
       }
    }

}