<?php


namespace App\Repositories\Disbursements;


interface DisbursementsRepositoryInterface
{
    public function store($request);

    public function index();

    public function show($request);

}