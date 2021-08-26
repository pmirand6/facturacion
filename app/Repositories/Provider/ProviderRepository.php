<?php


namespace App\Repositories\Provider;


use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProviderRepository implements ProviderRepositoryInterface
{

    public function saveClientErp()
    {
        // TODO: Implement saveClientErp() method.
    }

    public function readClientErp()
    {
        // TODO: Implement readClientErp() method.
    }

    public function enrollMarketplace($code)
    {
        // TODO: Implement enrollMarketplace() method.
    }


    public function store($request, $providerData = null)
    {
        try {

            $provider = Provider::firstOrCreate(
                [
                    'provider_email' => $request->email
                ],
                [
                    'identification_type' => $request->identification_type,
                    'identification_number' => $request->identification_number,
                    'provider_address' => $request->provider_address,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'zip_code' => $request->zip_code,
                    'street_name' => $request->street_name,
                    'street_number' => $request->street_number,
                    'country_code' => $request->country_code,
                    'state_code' => $request->state_code,
                    'erp_id' => $request->erp_id,
                    'locality_code' => $request->locality_code,
                    'marketplace_id' => $providerData['user_id'],
                    'access_token' => $providerData['access_token'],
                    'token_type' => $providerData['token_type'],
                    'expires_in' => $providerData['expires_in'],
                    'scope' => $providerData['scope'],
                    'refresh_token' => $providerData['refresh_token'],
                    'public_key' => $providerData['public_key'],
                ]);

            Log::info(json_encode([
                'method' => __METHOD__,
                'provider_id' => $provider->id
            ]));

            return response()->json([
                'error' => false,
                'message' => $provider->id
            ]);

        } catch (\Exception $e) {
            Log::error(json_encode([
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]));

            return response()->json([
                'error' => true,
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function storeFromPayment($request)
    {
        // TODO: Implement storeFromPayment() method.
    }

    public function getByEmail($email): JsonResponse
    {
        try {
            $provider = Provider::where('provider_email', $email)->firstOrFail();

            return response()->json([
                'error' => false,
                'data' => $provider->id
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

    }
}