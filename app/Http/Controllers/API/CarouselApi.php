<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Carousels",
 *     description="API endpoints for managing baggage carousel information"
 * )
 */

class CarouselApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/carousels",
     *     summary="Get all active carousels",
     *     description="Retrieve all active baggage carousels",
     *     tags={"Carousels"},
     *     @OA\Response(
     *         response=200,
     *         description="Carousels retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="Id", type="integer", example=1),
     *                 @OA\Property(property="CarouselName", type="string", example="C1"),
     *                 @OA\Property(property="Terminal", type="string", example="T1"),
     *                 @OA\Property(property="Status", type="integer", example=1)
     *             )),
     *             @OA\Property(property="count", type="integer", example=8)
     *         )
     *     )
     * )
     */
    public function getActiveCarousels()
    {
        try {
            $obj = new Carousel();
            $items = $obj->getActiveCarousels();
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving carousels: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/carousels",
     *     summary="Create new carousel",
     *     description="Create a new baggage carousel with the provided information",
     *     tags={"Carousels"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"CarouselName"},
     *             @OA\Property(property="CarouselName", type="string", example="C1", description="Carousel name/number"),
     *             @OA\Property(property="Terminal", type="string", example="T1", description="Terminal designation"),
     *             @OA\Property(property="CarouselType", type="string", example="International", description="Carousel type"),
     *             @OA\Property(property="Capacity", type="integer", example=500, description="Carousel capacity"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Carousel created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Carousel created successfully"),
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
    public function createCarousel(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'CarouselName' => 'required|string|max:20',
                'Terminal' => 'nullable|string|max:10',
                'CarouselType' => 'nullable|string|max:50',
                'Capacity' => 'nullable|integer',
                'Status' => 'integer|in:0,1'
            ]);

            // Set default values
            $validatedData['CreatedDate'] = now();
            $validatedData['Status'] = $validatedData['Status'] ?? 1;

            $obj = new Carousel();
            $result = $obj->insertCarousel($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Carousel created successfully',
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
                'message' => 'Error creating carousel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/carousels/{id}",
     *     summary="Update carousel",
     *     description="Update an existing carousel by ID",
     *     tags={"Carousels"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Carousel ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="CarouselName", type="string", example="C2", description="Carousel name/number"),
     *             @OA\Property(property="Terminal", type="string", example="T1", description="Terminal designation"),
     *             @OA\Property(property="CarouselType", type="string", example="Domestic", description="Carousel type"),
     *             @OA\Property(property="Capacity", type="integer", example=400, description="Carousel capacity"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carousel updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Carousel updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Carousel not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Carousel not found")
     *         )
     *     )
     * )
     */
    public function updateCarousel(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'CarouselName' => 'nullable|string|max:20',
                'Terminal' => 'nullable|string|max:10',
                'CarouselType' => 'nullable|string|max:50',
                'Capacity' => 'nullable|integer',
                'Status' => 'nullable|integer|in:0,1'
            ]);

            // Set modify date
            $validatedData['ModifiDate'] = now();

            $obj = new Carousel();
            $result = $obj->updateCarousel($validatedData, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Carousel updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Carousel not found'
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
                'message' => 'Error updating carousel: ' . $e->getMessage()
            ], 500);
        }
    }
}
