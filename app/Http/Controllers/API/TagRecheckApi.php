<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TagRecheck;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="TagRecheck",
 *     description="API endpoints for managing TagRecheck records - CRUD operations for baggage recheck data"
 * )
 */

class TagRecheckApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/tag-recheck/{date}",
     *     summary="Get TagRecheck records by date",
     *     description="Retrieve all TagRecheck records for a specific date",
     *     tags={"TagRecheck"},
     *     @OA\Parameter(
     *         name="date",
     *         in="path",
     *         required=true,
     *         description="Date in Y-m-d format",
     *         @OA\Schema(type="string", format="date", example="2025-03-29")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Records retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="Id", type="integer", example=126),
     *                 @OA\Property(property="FlightId", type="string", example="VJ870"),
     *                 @OA\Property(property="ScheduledDatetime", type="string", example="2025-03-29 01:00:00"),
     *                 @OA\Property(property="NamePassenger", type="string", example="KIM SOOJINMS"),
     *                 @OA\Property(property="SeatNumber", type="string", example="15F"),
     *                 @OA\Property(property="TagNumber", type="string", example="0523026119"),
     *                 @OA\Property(property="Status", type="integer", example=1)
     *             )),
     *             @OA\Property(property="count", type="integer", example=10)
     *         )
     *     )
     * )
     */
    public function getTagRecheckByDate($selectedDate)
    {
        try {
            $startDate = Carbon::parse($selectedDate)->setTime(0, 0, 0);
            $endDate = Carbon::parse($selectedDate)->setTime(23, 59, 59);
            
            $obj = new TagRecheck();
            $items = $obj->getTagRecheckAPI($startDate, $endDate);
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving TagRecheck data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/tag-recheck/{flightId}/{date}",
     *     summary="Get TagRecheck records by flight ID and date",
     *     description="Retrieve TagRecheck records for a specific flight and date",
     *     tags={"TagRecheck"},
     *     @OA\Parameter(
     *         name="flightId",
     *         in="path",
     *         required=true,
     *         description="Flight ID (e.g., VJ870)",
     *         @OA\Schema(type="string", example="VJ870")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="path",
     *         required=true,
     *         description="Date in Y-m-d format",
     *         @OA\Schema(type="string", format="date", example="2025-03-29")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Records retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="count", type="integer", example=5)
     *         )
     *     )
     * )
     */
    public function getTagRecheckByFlight($flightId, $selectedDate)
    {
        try {
            $startDate = Carbon::parse($selectedDate)->setTime(0, 0, 0);
            $endDate = Carbon::parse($selectedDate)->setTime(23, 59, 59);
            
            $obj = new TagRecheck();
            $items = $obj->getTagRecheckByFlight($flightId, $startDate, $endDate);
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving TagRecheck data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/tag-recheck",
     *     summary="Create new TagRecheck record",
     *     description="Create a new baggage recheck record",
     *     tags={"TagRecheck"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"FlightId", "ScheduledDatetime", "NamePassenger"},
     *             @OA\Property(property="FlightId", type="string", example="VJ870"),
     *             @OA\Property(property="ScheduledDatetime", type="string", format="datetime", example="2025-03-29 01:00:00"),
     *             @OA\Property(property="NamePassenger", type="string", example="KIM SOOJINMS"),
     *             @OA\Property(property="SeatNumber", type="string", example="15F"),
     *             @OA\Property(property="TagNumber", type="string", example="0523026119"),
     *             @OA\Property(property="DeviceTypeRecheck", type="integer", enum={1,2,3}, example=1),
     *             @OA\Property(property="Status", type="integer", enum={1,2,3,4}, example=1),
     *             @OA\Property(property="RouteName", type="string", example="TAE"),
     *             @OA\Property(property="HandlerDep", type="string", example="SAGS"),
     *             @OA\Property(property="CounterDetail", type="string", example="H6,H7,H8"),
     *             @OA\Property(property="City", type="string", example="DAEGU")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="TagRecheck record created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="TagRecheck record created successfully"),
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
    public function createTagRecheck(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'FlightId' => 'required|string|max:15',
                'ScheduledDatetime' => 'required|date',
                'NamePassenger' => 'required|string|max:128',
                'SeatNumber' => 'nullable|string|max:11',
                'TagNumber' => 'nullable|string|max:30',
                'DeviceTypeRecheck' => 'integer|in:1,2,3',
                'Status' => 'integer|in:1,2,3,4',
                'RouteName' => 'nullable|string|max:50',
                'HandlerDep' => 'nullable|string|max:10',
                'CounterDetail' => 'nullable|string|max:50',
                'CheckinDatetime' => 'nullable|date',
                'Description' => 'nullable|string|max:256',
                'DepartureMovementId' => 'nullable|integer',
                'IsBsm' => 'integer|in:0,1',
                'IsKorea' => 'integer|in:0,1',
                'City' => 'nullable|string|max:128',
                'Noted' => 'nullable|string'
            ]);

            // Set default values
            $validatedData['RecheckDatetime'] = now();
            $validatedData['DeviceTypeRecheck'] = $validatedData['DeviceTypeRecheck'] ?? 1;
            $validatedData['Status'] = $validatedData['Status'] ?? 1;
            $validatedData['IsDelete'] = 0;
            $validatedData['IsBsm'] = $validatedData['IsBsm'] ?? 1;
            $validatedData['IsKorea'] = $validatedData['IsKorea'] ?? 0;

            $obj = new TagRecheck();
            $result = $obj->insertTagRecheck($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'TagRecheck record created successfully',
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
                'message' => 'Error creating TagRecheck record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/tag-recheck/{id}",
     *     summary="Update TagRecheck record",
     *     description="Update an existing TagRecheck record",
     *     tags={"TagRecheck"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="TagRecheck ID",
     *         @OA\Schema(type="integer", example=126)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="FlightId", type="string", example="VJ870"),
     *             @OA\Property(property="ScheduledDatetime", type="string", format="datetime", example="2025-03-29 01:00:00"),
     *             @OA\Property(property="NamePassenger", type="string", example="KIM SOOJINMS"),
     *             @OA\Property(property="SeatNumber", type="string", example="15F"),
     *             @OA\Property(property="Status", type="integer", enum={1,2,3,4}, example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="TagRecheck record updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="TagRecheck record updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TagRecheck record not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="TagRecheck record not found")
     *         )
     *     )
     * )
     */
    public function updateTagRecheck(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'FlightId' => 'nullable|string|max:15',
                'ScheduledDatetime' => 'nullable|date',
                'NamePassenger' => 'nullable|string|max:128',
                'SeatNumber' => 'nullable|string|max:11',
                'TagNumber' => 'nullable|string|max:30',
                'DeviceTypeRecheck' => 'nullable|integer|in:1,2,3',
                'Status' => 'nullable|integer|in:1,2,3,4',
                'RouteName' => 'nullable|string|max:50',
                'HandlerDep' => 'nullable|string|max:10',
                'CounterDetail' => 'nullable|string|max:50',
                'CheckinDatetime' => 'nullable|date',
                'Description' => 'nullable|string|max:256',
                'DepartureMovementId' => 'nullable|integer',
                'IsBsm' => 'nullable|integer|in:0,1',
                'IsKorea' => 'nullable|integer|in:0,1',
                'City' => 'nullable|string|max:128',
                'Noted' => 'nullable|string'
            ]);

            // Set modify datetime
            $validatedData['ModifyDatetime'] = now();

            $obj = new TagRecheck();
            $result = $obj->updateTagRecheck($validatedData, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'TagRecheck record updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'TagRecheck record not found'
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
                'message' => 'Error updating TagRecheck record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/tag-recheck/{id}",
     *     summary="Delete TagRecheck record",
     *     description="Soft delete a TagRecheck record (sets IsDelete = 1)",
     *     tags={"TagRecheck"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="TagRecheck ID",
     *         @OA\Schema(type="integer", example=126)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="TagRecheck record deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="TagRecheck record deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TagRecheck record not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="TagRecheck record not found")
     *         )
     *     )
     * )
     */
    public function deleteTagRecheck($id)
    {
        try {
            $obj = new TagRecheck();
            $result = $obj->deleteTagRecheck($id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'TagRecheck record deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'TagRecheck record not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting TagRecheck record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/tag-recheck/{id}/finish",
     *     summary="Mark TagRecheck as finished",
     *     description="Mark a TagRecheck record as finished (Status = 4, set FinishedDatetime)",
     *     tags={"TagRecheck"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="TagRecheck ID",
     *         @OA\Schema(type="integer", example=126)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="TagRecheck marked as finished successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="TagRecheck marked as finished successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TagRecheck record not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="TagRecheck record not found")
     *         )
     *     )
     * )
     */
    public function finishTagRecheck($id)
    {
        try {
            $updateData = [
                'Status' => 4, // Finished status
                'FinishedDatetime' => now(),
                'ModifyDatetime' => now()
            ];

            $obj = new TagRecheck();
            $result = $obj->updateTagRecheck($updateData, $id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'TagRecheck marked as finished successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'TagRecheck record not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error finishing TagRecheck record: ' . $e->getMessage()
            ], 500);
        }
    }
}
