<?php


namespace App\Repositories\Customer;


interface CustomerRepositoryInterface
{
    public function index();

    public function show($request);

    public function store($request);

    public function update($request);

    public function storeFromPayment($request);

}