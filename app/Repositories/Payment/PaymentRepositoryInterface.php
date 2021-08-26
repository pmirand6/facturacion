<?php


namespace App\Repositories\Payment;


interface PaymentRepositoryInterface
{
    public function store($request, $customer_id);

    public function show($request);

    public function index();
}