<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Stand;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Stands",
 *     description="API endpoints for managing aircraft stand information"
 * )
 */

class StandApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/stands",
     *     summary="Get all active stands",
     *     description="Retrieve all active aircraft stands",
     *     tags={"Stands"},
     *     @OA\Response(
     *         response=200,
     *         description="Stands retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="Id", type="integer", example=1),
     *                 @OA\Property(property="StandName", type="string", example="S1"),
     *                 @OA\Property(property="Terminal", type="string", example="T1"),
     *                 @OA\Property(property="Status", type="integer", example=1)
     *             )),
     *             @OA\Property(property="count", type="integer", example=25)
     *         )
     *     )
     * )
     */
    public function getActiveStands()
    {
        try {
            $obj = new Stand();
            $items = $obj->getActiveStands();
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving stands: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/stands",
     *     summary="Create new stand",
     *     description="Create a new aircraft stand with the provided information",
     *     tags={"Stands"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"StandName"},
     *             @OA\Property(property="StandName", type="string", example="S1", description="Stand name/number"),
     *             @OA\Property(property="Terminal", type="string", example="T1", description="Terminal designation"),
     *             @OA\Property(property="StandType", type="string", example="Remote", description="Stand type"),
     *             @OA\Property(property="AircraftCategory", type="string", example="Category C", description="Aircraft category"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Stand created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Stand created successfully"),
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
    public function createStand(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'StandName' => 'required|string|max:20',
                'Terminal' => 'nullable|string|max:10',
                'StandType' => 'nullable|string|max:50',
                'AircraftCategory' => 'nullable|string|max:50',
                'Status' => 'integer|in:0,1'
            ]);

            // Set default values
            $validatedData['CreatedDate'] = now();
            $validatedData['Status'] = $validatedData['Status'] ?? 1;

            $obj = new Stand();
            $result = $obj->insertStand($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Stand created successfully',
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
                'message' => 'Error creating stand: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/stands/{id}",
     *     summary="Update stand",
     *     description="Update an existing stand by ID",
     *     tags={"Stands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Stand ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="StandName", type="string", example="S2", description="Stand name/number"),
     *             @OA\Property(property="Terminal", type="string", example="T1", description="Terminal designation"),
     *             @OA\Property(property="StandType", type="string", example="Contact", description="Stand type"),
     *             @OA\Property(property="AircraftCategory", type="string", example="Category D", description="Aircraft category"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stand updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Stand updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Stand not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Stand not found")
     *         )
     *     )
     * )
     */
    public function updateStand(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'StandName' => 'nullable|string|max:20',
                'Terminal' => 'nullable|string|max:10',
                'StandType' => 'nullable|string|max:50',
                'AircraftCategory' => 'nullable|string|max:50',
                'Status' => 'nullable|integer|in:0,1'
            ]);

            // Set modify date
            $validatedData['ModifiDate'] = now();

            $obj = new Stand();
            $result = $obj->updateStand($validatedData, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stand updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Stand not found'
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
                'message' => 'Error updating stand: ' . $e->getMessage()
            ], 500);
        }
    }
}
