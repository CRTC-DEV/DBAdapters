<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Chute;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Chutes",
 *     description="API endpoints for managing baggage chute information"
 * )
 */

class ChuteApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/chutes",
     *     summary="Get all active chutes",
     *     description="Retrieve all active baggage chutes",
     *     tags={"Chutes"},
     *     @OA\Response(
     *         response=200,
     *         description="Chutes retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="Id", type="integer", example=1),
     *                 @OA\Property(property="ChuteName", type="string", example="CH01"),
     *                 @OA\Property(property="Terminal", type="string", example="T1"),
     *                 @OA\Property(property="Status", type="integer", example=1)
     *             )),
     *             @OA\Property(property="count", type="integer", example=10)
     *         )
     *     )
     * )
     */
    public function getActiveChutes()
    {
        try {
            $obj = new Chute();
            $items = $obj->getActiveChutes();
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving chutes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/chutes",
     *     summary="Create new chute",
     *     description="Create a new baggage chute with the provided information",
     *     tags={"Chutes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ChuteName"},
     *             @OA\Property(property="ChuteName", type="string", example="CH01", description="Chute name/number"),
     *             @OA\Property(property="Terminal", type="string", example="T1", description="Terminal designation"),
     *             @OA\Property(property="ChuteType", type="string", example="Departure", description="Chute type"),
     *             @OA\Property(property="Location", type="string", example="North Wing", description="Chute location"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chute created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Chute created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function createChute(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'ChuteName' => 'required|string|max:20',
                'Terminal' => 'nullable|string|max:10',
                'ChuteType' => 'nullable|string|max:50',
                'Location' => 'nullable|string|max:100',
                'Status' => 'integer|in:0,1'
            ]);

            // Set default values
            $validatedData['CreatedDate'] = now();
            $validatedData['Status'] = $validatedData['Status'] ?? 1;

            $obj = new Chute();
            $result = $obj->insertChute($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Chute created successfully',
                'data' => $result
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating chute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/chutes/{id}",
     *     summary="Update chute",
     *     description="Update an existing chute by ID",
     *     tags={"Chutes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Chute ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="ChuteName", type="string", example="CH02", description="Chute name/number"),
     *             @OA\Property(property="Terminal", type="string", example="T1", description="Terminal designation"),
     *             @OA\Property(property="ChuteType", type="string", example="Arrival", description="Chute type"),
     *             @OA\Property(property="Location", type="string", example="South Wing", description="Chute location"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chute updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Chute updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chute not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Chute not found")
     *         )
     *     )
     * )
     */
    public function updateChute(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'ChuteName' => 'nullable|string|max:20',
                'Terminal' => 'nullable|string|max:10',
                'ChuteType' => 'nullable|string|max:50',
                'Location' => 'nullable|string|max:100',
                'Status' => 'nullable|integer|in:0,1'
            ]);

            // Set modify date
            $validatedData['ModifiDate'] = now();

            $obj = new Chute();
            $result = $obj->updateChute($validatedData, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Chute updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Chute not found'
                ], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating chute: ' . $e->getMessage()
            ], 500);
        }
    }
}
