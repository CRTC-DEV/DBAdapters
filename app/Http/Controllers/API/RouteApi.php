<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Routes",
 *     description="API endpoints for managing flight route information"
 * )
 */

class RouteApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/routes",
     *     summary="Get all active routes",
     *     description="Retrieve all active flight routes",
     *     tags={"Routes"},
     *     @OA\Response(
     *         response=200,
     *         description="Routes retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="Id", type="integer", example=1),
     *                 @OA\Property(property="DepartureAirportId", type="integer", example=1),
     *                 @OA\Property(property="ArrivalAirportId", type="integer", example=2),
     *                 @OA\Property(property="Distance", type="number", example=1200.5),
     *                 @OA\Property(property="Status", type="integer", example=1)
     *             )),
     *             @OA\Property(property="count", type="integer", example=25)
     *         )
     *     )
     * )
     */
    public function getActiveRoutes()
    {
        try {
            $obj = new Route();
            $items = $obj->getActiveRoutes();
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving routes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/routes/{id}",
     *     summary="Get route by ID",
     *     description="Retrieve route information by ID",
     *     tags={"Routes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Route ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Route found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Route not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Route not found")
     *         )
     *     )
     * )
     */
    public function getRouteById($id)
    {
        try {
            $obj = new Route();
            $route = $obj->getRouteById($id);
            
            if ($route) {
                return response()->json([
                    'success' => true,
                    'data' => $route
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving route: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/routes",
     *     summary="Create new route",
     *     description="Create a new flight route with the provided information",
     *     tags={"Routes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"DepartureAirportId", "ArrivalAirportId"},
     *             @OA\Property(property="DepartureAirportId", type="integer", example=1, description="Departure airport ID"),
     *             @OA\Property(property="ArrivalAirportId", type="integer", example=2, description="Arrival airport ID"),
     *             @OA\Property(property="Distance", type="number", example=1200.5, description="Distance in kilometers"),
     *             @OA\Property(property="FlightTime", type="string", example="02:30:00", description="Flight time"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Route created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Route created successfully"),
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
    public function createRoute(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'DepartureAirportId' => 'required|integer',
                'ArrivalAirportId' => 'required|integer',
                'Distance' => 'nullable|numeric',
                'FlightTime' => 'nullable|string',
                'Status' => 'integer|in:0,1'
            ]);

            // Set default values
            $validatedData['CreatedDate'] = now();
            $validatedData['Status'] = $validatedData['Status'] ?? 1;

            $obj = new Route();
            $result = $obj->insertRoute($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Route created successfully',
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
                'message' => 'Error creating route: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/routes/{id}",
     *     summary="Update route",
     *     description="Update an existing route by ID",
     *     tags={"Routes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Route ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="DepartureAirportId", type="integer", example=1, description="Departure airport ID"),
     *             @OA\Property(property="ArrivalAirportId", type="integer", example=2, description="Arrival airport ID"),
     *             @OA\Property(property="Distance", type="number", example=1200.5, description="Distance in kilometers"),
     *             @OA\Property(property="FlightTime", type="string", example="02:30:00", description="Flight time"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Route updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Route updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Route not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Route not found")
     *         )
     *     )
     * )
     */
    public function updateRoute(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'DepartureAirportId' => 'nullable|integer',
                'ArrivalAirportId' => 'nullable|integer',
                'Distance' => 'nullable|numeric',
                'FlightTime' => 'nullable|string',
                'Status' => 'nullable|integer|in:0,1'
            ]);

            // Set modify date
            $validatedData['ModifiDate'] = now();

            $obj = new Route();
            $result = $obj->updateRoute($validatedData, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Route updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found'
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
                'message' => 'Error updating route: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/routes/{id}",
     *     summary="Delete route",
     *     description="Soft delete a route by setting status to 0",
     *     tags={"Routes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Route ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Route deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Route deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Route not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Route not found")
     *         )
     *     )
     * )
     */
    public function deleteRoute($id)
    {
        try {
            $obj = new Route();
            $result = $obj->deleteRoute($id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Route deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting route: ' . $e->getMessage()
            ], 500);
        }
    }
}
