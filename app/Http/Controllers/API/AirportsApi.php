<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Airports;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Airports",
 *     description="API endpoints for managing airport information"
 * )
 */

class AirportsApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/airports",
     *     summary="Get all active airports",
     *     description="Retrieve all active airports ordered by name",
     *     tags={"Airports"},
     *     @OA\Response(
     *         response=200,
     *         description="Airports retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="AirportId", type="integer", example=1),
     *                 @OA\Property(property="Name", type="string", example="Tan Son Nhat International Airport"),
     *                 @OA\Property(property="IataCode", type="string", example="SGN"),
     *                 @OA\Property(property="IcaoCode", type="string", example="VVTS"),
     *                 @OA\Property(property="City", type="string", example="Ho Chi Minh City"),
     *                 @OA\Property(property="Country", type="string", example="Vietnam")
     *             )),
     *             @OA\Property(property="count", type="integer", example=50)
     *         )
     *     )
     * )
     */
    public function getActiveAirports()
    {
        try {
            $obj = new Airports();
            $items = $obj->getActiveAirports();
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving airports: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/airports/iata/{iataCode}",
     *     summary="Get airport by IATA code",
     *     description="Retrieve airport information by IATA code",
     *     tags={"Airports"},
     *     @OA\Parameter(
     *         name="iataCode",
     *         in="path",
     *         required=true,
     *         description="IATA code (e.g., SGN, HAN)",
     *         @OA\Schema(type="string", example="SGN")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Airport found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="AirportId", type="integer", example=1),
     *                 @OA\Property(property="Name", type="string", example="Tan Son Nhat International Airport"),
     *                 @OA\Property(property="IataCode", type="string", example="SGN"),
     *                 @OA\Property(property="IcaoCode", type="string", example="VVTS"),
     *                 @OA\Property(property="City", type="string", example="Ho Chi Minh City"),
     *                 @OA\Property(property="Country", type="string", example="Vietnam")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Airport not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Airport not found")
     *         )
     *     )
     * )
     */
    public function getAirportByIataCode($iataCode)
    {
        try {
            $obj = new Airports();
            $airport = $obj->getAirportByIataCode($iataCode);
            
            if ($airport) {
                return response()->json([
                    'success' => true,
                    'data' => $airport
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Airport not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving airport: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/airports/city/{city}",
     *     summary="Get airports by city",
     *     description="Retrieve all airports in a specific city",
     *     tags={"Airports"},
     *     @OA\Parameter(
     *         name="city",
     *         in="path",
     *         required=true,
     *         description="City name",
     *         @OA\Schema(type="string", example="Ho Chi Minh City")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Airports retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="AirportId", type="integer", example=1),
     *                 @OA\Property(property="Name", type="string", example="Tan Son Nhat International Airport"),
     *                 @OA\Property(property="IataCode", type="string", example="SGN"),
     *                 @OA\Property(property="IcaoCode", type="string", example="VVTS"),
     *                 @OA\Property(property="City", type="string", example="Ho Chi Minh City"),
     *                 @OA\Property(property="Country", type="string", example="Vietnam")
     *             )),
     *             @OA\Property(property="count", type="integer", example=2)
     *         )
     *     )
     * )
     */
    public function getAirportsByCity($city)
    {
        try {
            $obj = new Airports();
            $airports = $obj->getAirportsByCity($city);
            
            return response()->json([
                'success' => true,
                'data' => $airports,
                'count' => $airports->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving airports: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/airports",
     *     summary="Create new airport",
     *     description="Create a new airport with the provided information",
     *     tags={"Airports"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"AirportId", "Name"},
     *             @OA\Property(property="AirportId", type="integer", example=1, description="Unique airport ID"),
     *             @OA\Property(property="Name", type="string", example="Tan Son Nhat International Airport", description="Airport name"),
     *             @OA\Property(property="IataCode", type="string", example="SGN", description="IATA code"),
     *             @OA\Property(property="IcaoCode", type="string", example="VVTS", description="ICAO code"),
     *             @OA\Property(property="City", type="string", example="Ho Chi Minh City", description="City name"),
     *             @OA\Property(property="Country", type="string", example="Vietnam", description="Country name"),
     *             @OA\Property(property="CustomsType", type="string", example="International", description="Customs type"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Airport created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Airport created successfully"),
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
    public function createAirport(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'AirportId' => 'required|integer|unique:Airports,AirportId',
                'Name' => 'required|string|max:128',
                'IataCode' => 'nullable|string|max:20',
                'IcaoCode' => 'nullable|string|max:20',
                'City' => 'nullable|string|max:128',
                'Country' => 'nullable|string|max:128',
                'CustomsType' => 'nullable|string|max:20',
                'Status' => 'integer|in:0,1'
            ]);

            // Set default values
            $validatedData['CreatedDate'] = now();
            $validatedData['Status'] = $validatedData['Status'] ?? 1;

            $obj = new Airports();
            $result = $obj->insertAirport($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Airport created successfully',
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
                'message' => 'Error creating airport: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/airports/{airportId}",
     *     summary="Update airport",
     *     description="Update an existing airport by ID",
     *     tags={"Airports"},
     *     @OA\Parameter(
     *         name="airportId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Airport ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="Name", type="string", example="Tan Son Nhat International Airport", description="Airport name"),
     *             @OA\Property(property="IataCode", type="string", example="SGN", description="IATA code"),
     *             @OA\Property(property="IcaoCode", type="string", example="VVTS", description="ICAO code"),
     *             @OA\Property(property="City", type="string", example="Ho Chi Minh City", description="City name"),
     *             @OA\Property(property="Country", type="string", example="Vietnam", description="Country name"),
     *             @OA\Property(property="CustomsType", type="string", example="International", description="Customs type"),
     *             @OA\Property(property="Status", type="integer", example=1, description="Status (0=inactive, 1=active)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Airport updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Airport updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Airport not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Airport not found")
     *         )
     *     )
     * )
     */
    public function updateAirport(Request $request, $airportId)
    {
        try {
            $validatedData = $request->validate([
                'Name' => 'nullable|string|max:128',
                'IataCode' => 'nullable|string|max:20',
                'IcaoCode' => 'nullable|string|max:20',
                'City' => 'nullable|string|max:128',
                'Country' => 'nullable|string|max:128',
                'CustomsType' => 'nullable|string|max:20',
                'Status' => 'nullable|integer|in:0,1'
            ]);

            // Set modify date
            $validatedData['ModifiDate'] = now();

            $obj = new Airports();
            $result = $obj->updateAirport($validatedData, $airportId);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Airport updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Airport not found'
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
                'message' => 'Error updating airport: ' . $e->getMessage()
            ], 500);
        }
    }
}
