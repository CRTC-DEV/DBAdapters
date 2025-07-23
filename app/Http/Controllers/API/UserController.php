<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0",
 *     description="API documentation for my Laravel app"
 * )
 */

/**
 * @OA\Get(
 *     path="/api/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     @OA\Response(response=200, description="Successful response"),
 *     @OA\Response(response=401, description="Unauthorized"),
 * )
 */
class UserController extends Controller
{
    public function index()
    {
        return response()->json(['users' => []]);
    }
}
