<?php


namespace App\Http\Controllers\Api\v1;

use App\Repositories\Provider\ProviderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class MarketPlaceController extends Controller
{

    /**
     * @var ProviderRepositoryInterface
     */
    private $providerRepository;

    public function __construct(ProviderRepositoryInterface $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }

    public function assoc(Request $request): JsonResponse
    {
        $response = Http::asForm()->post('https://api.mercadopago.com/oauth/token', [
            'client_secret' => env('ACCESS_TOKEN_TEST'),
            'grant_type' => 'authorization_code',
            'code' => $request->vendor_code,
            'redirect_uri' => $request->redirect_uri
        ]);

        $respuesta = $response->json();

        if ($response->ok()) {
            Log::info(json_encode([
                'method' => __METHOD__,
                'request' => $request->all(),
                'response' => $respuesta
            ]));

            $this->providerRepository->store($request, $respuesta);

            return response()->json([
                'error' => false,
                'response' => $respuesta['user_id'] //devuelvo id de MP
            ]);
        }

        Log::error(json_encode([
            'method' => __METHOD__,
            'request' => $request->all(),
            'response' => $respuesta
        ]));

        return response()->json([
            'error' => true,
            'response' => $respuesta['message']
        ]);
    }

}