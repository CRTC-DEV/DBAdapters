<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Gate;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Gates",
 *     description="API endpoints for managing airport gate information"
 * )
 */

class GateApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/gates",
     *     summary="Get all active gates",
     *     description="Retrieve all active airport gates",
     *     tags={"Gates"},
     *     @OA\Response(
     *         response=200,
     *         description="Gates retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="Id", type="integer", example=1),
     *                 @OA\Property(property="GateName", type="string", example="A1"),
     *                 @OA\Property(property="Terminal", type="string", example="T1"),
     *                 @OA\Property(property="Status", type="integer", example=1)
     *             )),
     *             @OA\Property(property="count", type="integer", example=25)
     *         )
     *     )
     * )
     */
    public function getActiveGates()
    {
        try {
            $obj = new Gate();
            $items = $obj->getActiveGates();
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving gates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/gates",
     *     summary="Create new gate",
     *     description="Create a new airport gate with the provided information",
     *     tags={"Gates"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"GateName"},
     *             @OA\Property(property="GateName", type="string", example="A1", description="Gate name/number"),
     *             @OA\Property(property="Terminal", type="string", example="T1", description="Terminal designation"),
     *             @OA\Property(property="GateType", type="string", example="Domestic", description="Gate type"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Gate created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Gate created successfully"),
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
    public function createGate(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'GateName' => 'required|string|max:20',
                'Terminal' => 'nullable|string|max:10',
                'GateType' => 'nullable|string|max:50',
                'Status' => 'integer|in:0,1'
            ]);

            // Set default values
            $validatedData['CreatedDate'] = now();
            $validatedData['Status'] = $validatedData['Status'] ?? 1;

            $obj = new Gate();
            $result = $obj->insertGate($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Gate created successfully',
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
                'message' => 'Error creating gate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/gates/{id}",
     *     summary="Update gate",
     *     description="Update an existing gate by ID",
     *     tags={"Gates"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Gate ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="GateName", type="string", example="A2", description="Gate name/number"),
     *             @OA\Property(property="Terminal", type="string", example="T1", description="Terminal designation"),
     *             @OA\Property(property="GateType", type="string", example="International", description="Gate type"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gate updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Gate updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Gate not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gate not found")
     *         )
     *     )
     * )
     */
    public function updateGate(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'GateName' => 'nullable|string|max:20',
                'Terminal' => 'nullable|string|max:10',
                'GateType' => 'nullable|string|max:50',
                'Status' => 'nullable|integer|in:0,1'
            ]);

            // Set modify date
            $validatedData['ModifiDate'] = now();

            $obj = new Gate();
            $result = $obj->updateGate($validatedData, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Gate updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gate not found'
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
                'message' => 'Error updating gate: ' . $e->getMessage()
            ], 500);
        }
    }
}
