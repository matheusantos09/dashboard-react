<?php

namespace Modules\Core\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use JWTAuth;

/**
 * Class PermissionController
 *
 * @package Modules\Core\Http\Controllers
 */
class PermissionController extends Controller
{

    /**
     * @var
     */
    private $user;

    /**
     * PermissionController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->toUser();
    }

    /**
     * @return JsonResponse
     */
    public function permissions()
    {
        try {

            $permissions = $this->user->getAllPermissions();

            if ($permissions->count() <= 0) {

                return response()->json([
                    'error'   => false,
                    'message' => 'Não foi possível processar sua requisição',
                    'content' => [],
                ]);

            }

            $arrayAux = [];

            foreach ($permissions as $permission) {
                $arrayAux[] = $permission->name;
            }

            return response()->json([
                'error'   => false,
                'message' => 'Permissões diponíveis para esse usuário',
                'content' => $arrayAux,
            ]);

        } catch (Exception $e) {

            return response()->json([
                'error'   => true,
                'message' => 'Não foi possível processar sua requisição',
                'content' => []
            ]);
        }
    }

}
