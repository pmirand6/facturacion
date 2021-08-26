<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Users;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show(Request $request)
    {
        $requestUser = $request->instance()->query('userData');
        $user = $this->userRepository->getByEmail($requestUser[0]['email']);
        return response()->json($user);
    }
}