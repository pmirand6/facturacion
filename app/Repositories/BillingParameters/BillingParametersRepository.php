<?php


namespace App\Repositories\BillingParameters;


use App\Models\BillingParameter;
use Exception;
use Illuminate\Http\JsonResponse;

class BillingParametersRepository implements BillingParametersRepositoryInterface
{

    public function store($request): JsonResponse
    {
        try {
            BillingParameter::truncate();

            BillingParameter::create([
                'mercado_pago_rsn' => $request->mercado_pago_rsn,
                'mercado_pago_alitaware' => $request->mercado_pago_alitaware,
                'commission_percent' => $request->commission_percent,
                'iva_percent' => $request->iva_percent,
                'commission_percent_rsn' => $request->commission_percent_rsn,
                'commission_percent_alitaware' => $request->commission_percent_alitaware,
                'cost_until_20kg' => $request->cost_until_20kg,
                'cost_higher_20kg' => $request->cost_higher_20kg,
                'cost_per_km_until_20kg' => $request->cost_per_km_until_20kg,
                'cost_per_km_higher_20kg' => $request->cost_per_km_higher_20kg,
                'url_api_rsn' => $request->url_api_rsn,
                'url_api_alitaware' => $request->url_api_alitaware,
                'user' => $request->user,
                'password' => $request->password,
                'client_id' => $request->client_id,
                'secret_key' => $request->secret_key,
                'company' => $request->company,
                'email_admin_rsn' => $request->email_admin_rsn,
            ]);

            return response()->json([
                'error' => false,
                'response' => "Parámetros Creados"
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'error' => true,
                'response' => $exception->getMessage()
            ]);
        }
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function update($request)
    {
        try {

        } catch (Exception $e) {

        }
    }

    public function show($request)
    {
        // TODO: Implement show() method.
    }
}