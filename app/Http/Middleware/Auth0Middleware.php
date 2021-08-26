<?php

namespace App\Http\Middleware;

use App\Repositories\Auth0\Auth0RepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Auth0\SDK\Exception\ApiException;
use Auth0\SDK\Exception\CoreException;
use Closure;
use Auth0\SDK\Exception\InvalidTokenException;
use Auth0\SDK\Helpers\JWKFetcher;
use Auth0\SDK\Helpers\Tokens\AsymmetricVerifier;
use Auth0\SDK\Helpers\Tokens\TokenVerifier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


class Auth0Middleware
{

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var Auth0RepositoryInterface
     */
    private $auth0Repository;
    /**
     * @var Auth
     */
    private $auth;

    public function __construct(UserRepositoryInterface $userRepository, Auth0RepositoryInterface $auth0Repository)
    {
        $this->userRepository = $userRepository;
        $this->auth0Repository = $auth0Repository;
    }

    /**
     * Run the request filter.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $scopeRequired
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $scopeRequired = null)
    {
        $token = $request->bearerToken();

        try {
            //Validacion de token no especificado
            if (!$token) {
                throw new Exception('Sin Token en Header Authorization');
            }
            try {
                $decodedToken = $this->validateAndDecode($token);
            } catch (Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => '[' . __class__ . '] ' .  $e->getMessage()
                ], 403);
            }

            //Obtengo la informaciÃ³n del Usuario en Auth0 a travÃ©s del token
            $userData = $this->userRepository->getAuth0($token);
            $userAuth0 = json_decode($userData);


            //Verifico si existe en base
            $userData = ($this->userRepository->show('email', $userAuth0->email));


            if ($userData->isEmpty()) {
                throw new Exception("Usuario con el email {$userAuth0->email} no encontrado");
            }

            $userSub = $userData[0]['sub'];
            if (!$userSub) {
                $params = collect([
                    'id' => $userData[0]['id'],
                    'sub' => $userAuth0->sub
                ]);
                $this->userRepository->updateSub($params);
            }


            //Si el token no tiene Scope me fijo si existe en la BD y le asigno un rol en base al perfil
            if (!$decodedToken['permissions']) {

                //Verifico si existe en la base de datos
                $userResponse = json_decode($userData, true);
                $userType = $userResponse[0]['userType'];

                //Verifico si el usuario tiene Sub de Auth0
                //en el caso de que no tenerlo, se lo asigno
                $userSub = $userResponse[0]['sub'];

                if (!$userSub) {
                    $request = collect([
                        'id' => $userResponse[0]['id'],
                        'sub' => $userAuth0->sub
                    ]);
                    $this->userRepository->updateSub($request);
                }

                //Busco el ID del Rol en Base al nombre que me devuelve la bÃºsqueda del usuario
                $rolResponse = $this->auth0Repository->getRolesAuth0($userType);
                $rol = json_decode($rolResponse, true);

                //Asigno el rol al usuario
                $this->auth0Repository->assignRole($userSub, $rol[0]['id']);

                throw new Exception('Se asignaron permisos, debe loguearse nuevamente');
            }


            if ($scopeRequired && !$this->tokenHasScope($decodedToken, $scopeRequired)) {
                throw new Exception('Insufficient scope');
            }


            $request->merge(array("userData" => $userData));

            return $next($request);
        } catch (Exception $e){
            return response()->json([
                'error' => true,
                'message' => '[' . __class__ . '] ' .  $e->getMessage()
            ]);
        }


    }

    public function validateAndDecode($token): array
    {
        try {
            $jwksUri = env('AUTH0_DOMAIN') . '.well-known/jwks.json';

            $jwksFetcher = new JWKFetcher(null, ['base_uri' => $jwksUri]);
            $signatureVerifier = new AsymmetricVerifier($jwksFetcher);
            $tokenVerifier = new TokenVerifier(env('AUTH0_DOMAIN'), env('AUTH0_AUD'), $signatureVerifier);
            return $tokenVerifier->verify($token);
        } catch (InvalidTokenException $e) {
            throw $e;
        }
    }

    /**
     * Check if a token has a specific scope.
     *
     * @param array $token - JWT access token to check.
     * @param string $scopeRequired - Scope to check for.
     *
     * @return bool
     */
    protected function tokenHasScope($token, $scopeRequired)
    {
        if (empty($token['scope'])) {
            return false;
        }

        $items = $token['permissions'];

        return in_array($scopeRequired, $items);

    }

}