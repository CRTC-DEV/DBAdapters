<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Airlines;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Airlines",
 *     description="API endpoints for managing airline information"
 * )
 */

class AirlinesApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/airlines",
     *     summary="Get all active airlines",
     *     description="Retrieve all active airlines ordered by name",
     *     tags={"Airlines"},
     *     @OA\Response(
     *         response=200,
     *         description="Airlines retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="AirlineId", type="integer", example=1),
     *                 @OA\Property(property="Name", type="string", example="Vietnam Airlines"),
     *                 @OA\Property(property="IataCode", type="string", example="VN"),
     *                 @OA\Property(property="IcaoCode", type="string", example="HVN"),
     *                 @OA\Property(property="Handler", type="string", example="SAGS"),
     *                 @OA\Property(property="Status", type="integer", example=1)
     *             )),
     *             @OA\Property(property="count", type="integer", example=15)
     *         )
     *     )
     * )
     */
    public function getActiveAirlines()
    {
        try {
            $obj = new Airlines();
            $items = $obj->getActiveAirlines();
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving airlines: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/airlines/iata/{iataCode}",
     *     summary="Get airline by IATA code",
     *     description="Retrieve airline information by IATA code",
     *     tags={"Airlines"},
     *     @OA\Parameter(
     *         name="iataCode",
     *         in="path",
     *         required=true,
     *         description="IATA code (e.g., VN, VJ)",
     *         @OA\Schema(type="string", example="VN")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Airline found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="AirlineId", type="integer", example=1),
     *                 @OA\Property(property="Name", type="string", example="Vietnam Airlines"),
     *                 @OA\Property(property="IataCode", type="string", example="VN"),
     *                 @OA\Property(property="IcaoCode", type="string", example="HVN")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Airline not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Airline not found")
     *         )
     *     )
     * )
     */
    public function getAirlineByIataCode($iataCode)
    {
        try {
            $obj = new Airlines();
            $airline = $obj->getAirlineByIataCode($iataCode);
            
            if ($airline) {
                return response()->json([
                    'success' => true,
                    'data' => $airline
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Airline not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving airline: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/airlines",
     *     summary="Create new airline",
     *     description="Create a new airline record",
     *     tags={"Airlines"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"AirlineId", "Name"},
     *             @OA\Property(property="AirlineId", type="integer", example=25),
     *             @OA\Property(property="Name", type="string", example="New Airlines"),
     *             @OA\Property(property="IataCode", type="string", example="NA"),
     *             @OA\Property(property="IcaoCode", type="string", example="NEW"),
     *             @OA\Property(property="Handler", type="string", example="SAGS"),
     *             @OA\Property(property="Status", type="integer", enum={0,1}, example=1),
     *             @OA\Property(property="IsKorea", type="integer", enum={0,1}, example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Airline created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Airline created successfully"),
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
    public function createAirline(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'AirlineId' => 'required|integer|unique:Airlines,AirlineId',
                'Name' => 'required|string|max:128',
                'IataCode' => 'nullable|string|max:20',
                'IcaoCode' => 'nullable|string|max:20',
                'Handler' => 'nullable|string|max:10',
                'Status' => 'integer|in:0,1',
                'IsKorea' => 'integer|in:0,1',
                'Add_0' => 'nullable|integer',
                'RepresentativeContact' => 'nullable|string|max:30',
                'AirportContact' => 'nullable|string|max:30',
                'MeastroDCS' => 'boolean'
            ]);

            // Set default values
            $validatedData['CreateDate'] = now();
            $validatedData['Status'] = $validatedData['Status'] ?? 1;
            $validatedData['IsKorea'] = $validatedData['IsKorea'] ?? 0;
            $validatedData['MeastroDCS'] = $validatedData['MeastroDCS'] ?? false;

            $obj = new Airlines();
            $result = $obj->insertAirline($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Airline created successfully',
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
                'message' => 'Error creating airline: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/airlines/{airlineId}",
     *     summary="Update airline",
     *     description="Update an existing airline record",
     *     tags={"Airlines"},
     *     @OA\Parameter(
     *         name="airlineId",
     *         in="path",
     *         required=true,
     *         description="Airline ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="Name", type="string", example="Updated Airline Name"),
     *             @OA\Property(property="IataCode", type="string", example="UA"),
     *             @OA\Property(property="IcaoCode", type="string", example="UPD"),
     *             @OA\Property(property="Status", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Airline updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Airline updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Airline not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Airline not found")
     *         )
     *     )
     * )
     */
    public function updateAirline(Request $request, $airlineId)
    {
        try {
            $validatedData = $request->validate([
                'Name' => 'nullable|string|max:128',
                'IataCode' => 'nullable|string|max:20',
                'IcaoCode' => 'nullable|string|max:20',
                'Handler' => 'nullable|string|max:10',
                'Status' => 'nullable|integer',
                'IsKorea' => 'nullable|integer|in:0,1',
                'Add_0' => 'nullable|integer',
                'RepresentativeContact' => 'nullable|string|max:30',
                'AirportContact' => 'nullable|string|max:30',
                'MeastroDCS' => 'nullable|boolean',
                'Contact' => 'nullable|string',
                'RegularCheckin' => 'nullable|string'
            ]);
            // Set modify date
            $validatedData['ModifiDate'] = now();

            $obj = new Airlines();
            $result = $obj->updateAirline($validatedData, $airlineId);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Airline updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Airline not found'
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
                'message' => 'Error updating airline: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/airlines/search",
     *     summary="Search airlines",
     *     description="Search airlines by name or ICAO code",
     *     tags={"Airlines"},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         required=true,
     *         description="Search keyword (name or ICAO code)",
     *         @OA\Schema(type="string", example="Vietnam")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Search results",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="AirlineId", type="integer", example=1),
     *                 @OA\Property(property="Name", type="string", example="Vietnam Airlines"),
     *                 @OA\Property(property="IataCode", type="string", example="VN"),
     *                 @OA\Property(property="IcaoCode", type="string", example="HVN")
     *             )),
     *             @OA\Property(property="count", type="integer", example=3)
     *         )
     *     )
     * )
     */
    public function searchAirlines(Request $request)
    {
        try {
            $keyword = $request->query('keyword', '');
            $obj = new Airlines();
            $items = $obj->searchAirlines($keyword);
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching airlines: ' . $e->getMessage()
            ], 500);
        }
    }
}