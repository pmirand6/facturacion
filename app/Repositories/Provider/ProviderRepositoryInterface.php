<?php


namespace App\Repositories\Provider;


interface ProviderRepositoryInterface
{
    public function saveClientErp();

    public function readClientErp();

    public function enrollMarketplace($code);

    public function store($request, $providerData = null);

    public function storeFromPayment($request);

    public function getByEmail($email);

}