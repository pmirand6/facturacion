<?php


namespace App\Repositories\BillingParameters;


interface BillingParametersRepositoryInterface
{
    public function store($request);

    public function index();

    public function update($request);

    public function show($request);
}