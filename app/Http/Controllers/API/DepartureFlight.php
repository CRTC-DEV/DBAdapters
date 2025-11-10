<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

    /**
    * @OA\Tag(
    *     name="DepartureFlight",
    *     description="API for DepartureFlight"
    * )
    */

class DepartureFlight extends Controller
{
    
    
    /**
     * @OA\Get(
     *     path="/api/departureflight/{id}",
     *     summary="Get DepartureFlight resources",
     *     tags={"DepartureFlight"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function index()
    {
        // Logic for fetching all resources
    }

    /**
     * @OA\Post(
     *     path="/api/departureflight",
     *     summary="Get DepartureFlight resource",
     *     tags={"DepartureFlight"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"field1","field2","field3"},
     *             @OA\Property(property="field1", type="string", example=1),
     *             @OA\Property(property="field2", type="string", example=1),
     *             @OA\Property(property="field3", type="string", example=1),
     *             type="object"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Resource created"
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Logic for storing a new resource
    }

    /**
     * @OA\Put(
     *     path="/api/departureflight/{id}",
     *     summary="Update a DepartureFlight resource",
     *     tags={"DepartureFlight"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resource updated"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Logic for updating a resource
    }

    /**
     * @OA\Delete(
     *     path="/api/departureflight/{id}",
     *     summary="Delete a DepartureFlight resource",
     *     tags={"DepartureFlight"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Resource deleted"
     *     )
     * )
     */
    public function destroy($id)
    {
        // Logic for deleting a resource
    }
}
