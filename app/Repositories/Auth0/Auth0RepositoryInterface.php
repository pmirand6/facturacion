<?php
/**
 * Class Auth0RepositoryInterface
 * @package App\Repositories\Auth0
 * Author: Pablo Miranda
 * Project: Feriame - Logistica
 * 13/12/20 00:37
 */

namespace App\Repositories\Auth0;


interface Auth0RepositoryInterface
{
    public function getToken();

    public function getUserInfo($jwtToken);

    public function getRolesAuth0($rolName);

    public function assignRole($userSub, $rolId);

}
