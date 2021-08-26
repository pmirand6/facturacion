<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\BillingParameter;
use App\Repositories\BillingParameters\BillingParametersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BillingParameterController extends Controller
{

    /**
     * @var BillingParametersRepositoryInterface
     */
    private $billingParameterRepository;

    public function __construct(BillingParametersRepositoryInterface $billingParametersRepository)
    {
        $this->billingParameterRepository = $billingParametersRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->billingParameterRepository->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->billingParameterRepository->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        return $this->billingParameterRepository->show($request);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        return $this->billingParameterRepository->update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BillingParameter $billingParameter
     * @return Response
     */
    public function destroy(BillingParameter $billingParameter)
    {
        //
    }
}
