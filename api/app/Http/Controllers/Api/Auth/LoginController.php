<?php

namespace App\Http\Controllers\Api\Auth;

use App\Traits\ResponseTrait;
use App\Traits\ValidationTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Api\Auth
 */
class LoginController extends Controller
{

    use ValidationTrait;
    use ResponseTrait;

    /**
     * @param Request $request
     * @param JWTAuth $JWTAuth
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function login(Request $request, JWTAuth $JWTAuth)
    {

        try {

            $this->validator($request->all(), [
                'email'    => 'required|email',
                'password' => 'required|max:191'
            ]);

            $credentials = $request->only(['email', 'password']);

            $token = auth('api')->attempt($credentials);

            if (!$token) {
                return $this->responseJson(true, 'Usuário não encontrado', 400);
            }

            /*
             * @TODO alterar tempo máximo do token e usar revalidação do token
             * */

            return response()->json([
                'error'      => false,
                'token'      => $token,
                'expires_in' => auth('api')->factory()->getTTL() * 60000
            ]);

        } catch (JWTException $e) {

            return $this->responseJson(true, 'Não foi possível fazer seu login tente novamente em alguns instantes',
                500, $e);

        } catch (Exception $e) {

            return $this->responseJson(true, 'Não foi possível fazer seu login tente novamente em alguns instantes',
                500, $e);

        }

    }
}
