<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Aircrafts;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Aircrafts",
 *     description="API endpoints for managing aircraft information"
 * )
 */

class AircraftsApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/aircrafts",
     *     summary="Get all active aircrafts",
     *     description="Retrieve all active aircrafts ordered by registration",
     *     tags={"Aircrafts"},
     *     @OA\Response(
     *         response=200,
     *         description="Aircrafts retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="AircraftId", type="integer", example=1),
     *                 @OA\Property(property="Registration", type="string", example="VN-A321"),
     *                 @OA\Property(property="AircraftTypeId", type="integer", example=5),
     *                 @OA\Property(property="AirlineId", type="integer", example=1),
     *                 @OA\Property(property="Status", type="integer", example=1)
     *             )),
     *             @OA\Property(property="count", type="integer", example=50)
     *         )
     *     )
     * )
     */
    public function getActiveAircrafts()
    {
        try {
            $obj = new Aircrafts();
            $items = $obj->getActiveAircrafts();
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving aircrafts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/aircrafts/registration/{registration}",
     *     summary="Get aircraft by registration",
     *     description="Retrieve aircraft information by registration number",
     *     tags={"Aircrafts"},
     *     @OA\Parameter(
     *         name="registration",
     *         in="path",
     *         required=true,
     *         description="Aircraft registration (e.g., VN-A321)",
     *         @OA\Schema(type="string", example="VN-A321")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Aircraft found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="AircraftId", type="integer", example=1),
     *                 @OA\Property(property="Registration", type="string", example="VN-A321"),
     *                 @OA\Property(property="AircraftTypeId", type="integer", example=5),
     *                 @OA\Property(property="AirlineId", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aircraft not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Aircraft not found")
     *         )
     *     )
     * )
     */
    public function getAircraftByRegistration($registration)
    {
        try {
            $obj = new Aircrafts();
            $aircraft = $obj->getAircraftByRegistration($registration);
            
            if ($aircraft) {
                return response()->json([
                    'success' => true,
                    'data' => $aircraft
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Aircraft not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving aircraft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/aircrafts",
     *     summary="Create new aircraft",
     *     description="Create a new aircraft record",
     *     tags={"Aircrafts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"AircraftId", "Registration", "AircraftTypeId", "AirlineId"},
     *             @OA\Property(property="AircraftId", type="integer", example=100),
     *             @OA\Property(property="Registration", type="string", example="VN-A350"),
     *             @OA\Property(property="AircraftTypeId", type="integer", example=10),
     *             @OA\Property(property="AirlineId", type="integer", example=1),
     *             @OA\Property(property="Status", type="integer", enum={0,1}, example=1),
     *             @OA\Property(property="MaxPax", type="integer", example=300),
     *             @OA\Property(property="Mtow", type="integer", example=280000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Aircraft created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Aircraft created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createAircraft(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'AircraftId' => 'required|integer|unique:Aircrafts,AircraftId',
                'Registration' => 'required|string|max:20',
                'AircraftTypeId' => 'required|integer',
                'AirlineId' => 'required|integer',
                'Status' => 'integer|in:0,1',
                'MaxPax' => 'nullable|integer',
                'Mtow' => 'nullable|integer'
            ]);

            // Set default values
            $validatedData['CreatedDate'] = now();
            $validatedData['Status'] = $validatedData['Status'] ?? 1;

            $obj = new Aircrafts();
            $result = $obj->insertAircraft($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Aircraft created successfully',
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
                'message' => 'Error creating aircraft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/aircrafts/{aircraftId}",
     *     summary="Update aircraft",
     *     description="Update an existing aircraft record",
     *     tags={"Aircrafts"},
     *     @OA\Parameter(
     *         name="aircraftId",
     *         in="path",
     *         required=true,
     *         description="Aircraft ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="Registration", type="string", example="VN-A321-Updated"),
     *             @OA\Property(property="Status", type="integer", enum={0,1}, example=1),
     *             @OA\Property(property="MaxPax", type="integer", example=180)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Aircraft updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Aircraft updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aircraft not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Aircraft not found")
     *         )
     *     )
     * )
     */
    public function updateAircraft(Request $request, $aircraftId)
    {
        try {
            $validatedData = $request->validate([
                'Registration' => 'nullable|string|max:20',
                'AircraftTypeId' => 'nullable|integer',
                'AirlineId' => 'nullable|integer',
                'Status' => 'nullable|integer|in:0,1',
                'MaxPax' => 'nullable|integer',
                'Mtow' => 'nullable|integer'
            ]);

            // Set modify date
            $validatedData['ModifiDate'] = now();

            $obj = new Aircrafts();
            $result = $obj->updateAircraft($validatedData, $aircraftId);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Aircraft updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Aircraft not found'
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
                'message' => 'Error updating aircraft: ' . $e->getMessage()
            ], 500);
        }
    }
}
