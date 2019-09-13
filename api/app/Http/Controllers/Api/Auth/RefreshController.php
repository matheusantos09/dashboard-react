<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Auth;

class RefreshController extends Controller
{
    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return response()->json([
            'error'      => false,
            'token'      => auth('api')->refresh(),
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
