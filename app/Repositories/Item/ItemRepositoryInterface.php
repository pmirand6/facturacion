<?php

namespace App\Repositories\Item;

interface ItemRepositoryInterface
{
    public function store($request);

    public function show($request);

    public function index();

    public function updateByPoAndProviderId($purchaseOrder, $providerId, $disbursementId);

}