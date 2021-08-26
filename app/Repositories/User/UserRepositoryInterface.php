<?php
/**
 * Class UserRepositoryInterface
 * @package App\Repositories\User
 * Author: Pablo Miranda
 * Project: Feriame - Logistica
 * 12/12/20 21:43
 */

namespace App\Repositories\User;


interface UserRepositoryInterface
{
    public function index();

    public function show($field, $request);

    public function getAuth0($jwtToken);

    public function getRolesAuth0($rolName, $jwtToken);

    public function assignRole($userAuth, $roleId);

    public function updateSub($request);

    public function create($request);

    public function getByEmail($email);

}
