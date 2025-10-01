<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AircraftTypes;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Aircraft Types",
 *     description="API endpoints for managing aircraft type information"
 * )
 */

class AircraftTypesApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/aircraft-types",
     *     summary="Get all active aircraft types",
     *     description="Retrieve all active aircraft types ordered by name",
     *     tags={"Aircraft Types"},
     *     @OA\Response(
     *         response=200,
     *         description="Aircraft types retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="AircraftTypeId", type="integer", example=1),
     *                 @OA\Property(property="Name", type="string", example="Airbus A321"),
     *                 @OA\Property(property="IataCode", type="string", example="321"),
     *                 @OA\Property(property="IcaoCode", type="string", example="A321"),
     *                 @OA\Property(property="MaxPax", type="integer", example=220),
     *                 @OA\Property(property="Mtow", type="integer", example=93500)
     *             )),
     *             @OA\Property(property="count", type="integer", example=25)
     *         )
     *     )
     * )
     */
    public function getActiveAircraftTypes()
    {
        try {
            $obj = new AircraftTypes();
            $items = $obj->getActiveAircraftTypes();
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving aircraft types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get aircraft type by ICAO code
     */
    public function getAircraftTypeByIcaoCode($icaoCode)
    {
        try {
            $obj = new AircraftTypes();
            $aircraftType = $obj->getAircraftTypeByIcaoCode($icaoCode);
            
            if ($aircraftType) {
                return response()->json([
                    'success' => true,
                    'data' => $aircraftType
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Aircraft type not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving aircraft type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/aircraft-types",
     *     summary="Create new aircraft type",
     *     description="Create a new aircraft type with the provided information",
     *     tags={"Aircraft Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"AircraftTypeId", "Name"},
     *             @OA\Property(property="AircraftTypeId", type="integer", example=1, description="Unique aircraft type ID"),
     *             @OA\Property(property="Name", type="string", example="Airbus A321", description="Aircraft type name"),
     *             @OA\Property(property="IataCode", type="string", example="321", description="IATA code"),
     *             @OA\Property(property="IcaoCode", type="string", example="A321", description="ICAO code"),
     *             @OA\Property(property="MaxPax", type="integer", example=220, description="Maximum passengers"),
     *             @OA\Property(property="Mtow", type="integer", example=93500, description="Maximum takeoff weight"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Aircraft type created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Aircraft type created successfully"),
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
    public function createAircraftType(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'AircraftTypeId' => 'required|integer|unique:AircraftTypes,AircraftTypeId',
                'Name' => 'required|string|max:256',
                'IataCode' => 'nullable|string|max:20',
                'IcaoCode' => 'nullable|string|max:20',
                'MaxPax' => 'nullable|integer',
                'Mtow' => 'nullable|integer',
                'Status' => 'integer|in:0,1'
            ]);

            // Set default values
            $validatedData['CreatedDate'] = now();
            $validatedData['Status'] = $validatedData['Status'] ?? 1;

            $obj = new AircraftTypes();
            $result = $obj->insertAircraftType($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Aircraft type created successfully',
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
                'message' => 'Error creating aircraft type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/aircraft-types/{aircraftTypeId}",
     *     summary="Update aircraft type",
     *     description="Update an existing aircraft type by ID",
     *     tags={"Aircraft Types"},
     *     @OA\Parameter(
     *         name="aircraftTypeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Aircraft type ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="Name", type="string", example="Airbus A321 Neo", description="Aircraft type name"),
     *             @OA\Property(property="IataCode", type="string", example="32Q", description="IATA code"),
     *             @OA\Property(property="IcaoCode", type="string", example="A21N", description="ICAO code"),
     *             @OA\Property(property="MaxPax", type="integer", example=230, description="Maximum passengers"),
     *             @OA\Property(property="Mtow", type="integer", example=97000, description="Maximum takeoff weight"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Aircraft type updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Aircraft type updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aircraft type not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Aircraft type not found")
     *         )
     *     )
     * )
     */
    public function updateAircraftType(Request $request, $aircraftTypeId)
    {
        try {
            $validatedData = $request->validate([
                'Name' => 'nullable|string|max:256',
                'IataCode' => 'nullable|string|max:20',
                'IcaoCode' => 'nullable|string|max:20',
                'MaxPax' => 'nullable|integer',
                'Mtow' => 'nullable|integer',
                'Status' => 'nullable|integer|in:0,1'
            ]);

            // Set modify date
            $validatedData['ModifiDate'] = now();

            $obj = new AircraftTypes();
            $result = $obj->updateAircraftType($validatedData, $aircraftTypeId);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Aircraft type updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Aircraft type not found'
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
                'message' => 'Error updating aircraft type: ' . $e->getMessage()
            ], 500);
        }
    }
}
