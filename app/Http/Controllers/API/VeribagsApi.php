<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TagRecheck;
use App\Models\DepartureMovementView;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * API for processing barcode data and checking baggage recheck requirements
 * 
 * @OA\Tag(
 *     name="Barcode Processing",
 *     description="API endpoints for processing barcode data and baggage recheck operations"
 * )
 */

class VeribagsApi extends Controller
{
    /**
     * @OA\Get(
     *     path="/process-barcode",
     *     summary="Process barcode data for baggage recheck",
     *     description="Processes barcode scan data to determine if baggage recheck is required. First checks TagRecheck table, then DepartureMovementView if not found.",
     *     tags={"Barcode Processing"},
     *     @OA\Parameter(
     *         name="airline",
     *         in="query",
     *         required=true,
     *         description="Airline code (e.g., VJ, HY)",
     *         @OA\Schema(type="string", example="VJ")
     *     ),
     *     @OA\Parameter(
     *         name="flightNumber",
     *         in="query",
     *         required=true,
     *         description="Flight number (e.g., 870, 0562)",
     *         @OA\Schema(type="string", example="870")
     *     ),
     *     @OA\Parameter(
     *         name="flightDate",
     *         in="query",
     *         required=true,
     *         description="Flight date in Y-m-d format",
     *         @OA\Schema(type="string", format="date", example="2025-03-29")
     *     ),
     *     @OA\Parameter(
     *         name="seatNumber",
     *         in="query",
     *         required=false,
     *         description="Seat number (e.g., 15F, 009F - leading zeros will be handled)",
     *         @OA\Schema(type="string", example="15F")
     *     ),
     *     @OA\Parameter(
     *         name="lastName",
     *         in="query",
     *         required=false,
     *         description="Passenger last name",
     *         @OA\Schema(type="string", example="KIM")
     *     ),
     *     @OA\Parameter(
     *         name="firstName",
     *         in="query",
     *         required=false,
     *         description="Passenger first name",
     *         @OA\Schema(type="string", example="SOOJINMS")
     *     ),
     *     @OA\Parameter(
     *         name="languageCode",
     *         in="query",
     *         required=false,
     *         description="Language code for response message (EN, VI, KO, ZH)",
     *         @OA\Schema(type="string", enum={"EN", "VI", "KO", "ZH"}, example="EN")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success - Recheck required",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="Recheck", type="string", example="Yes"),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="Passenger", type="string", example="KIM SOOJINMS"),
     *             @OA\Property(property="Counter", type="string", example="H"),
     *             @OA\Property(property="message", type="string", example="Please proceed to the recheck counter at H")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No matching records found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No matching records found for the provided barcode data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing required parameters",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Missing required parameters: airline, flightNumber, flightDate")
     *         )
     *     )
     * )
     */
    public function processBarcodeData(Request $request)
    {
        // dd($request);
        // Get parameters from query string
        $airline = $request->query('airline');
        $flightNumber = $request->query('flightNumber');
        $flightDate = $request->query('flightDate');
        $seatNumber = $request->query('seatNumber');
        $lastName = $request->query('lastName');
        $firstName = $request->query('firstName');
        $languageCode = $request->query('languageCode', 'EN'); // Default to 'EN' if not provided

        // Validate required parameters
        if (!$airline || !$flightNumber || !$flightDate) {
            $errorMessages = [
                'EN' => 'Missing required parameters: airline, flightNumber, flightDate',
                'VI' => 'Thiếu tham số bắt buộc: airline, flightNumber, flightDate',
                'KO' => '필수 매개변수 누락: airline, flightNumber, flightDate',
                'ZH' => '缺少必需参数：airline, flightNumber, flightDate'
            ];

            return response()->json([
                'success' => false,
                'message' => $errorMessages[$languageCode] ?? $errorMessages['EN']
            ], 400);
        }

        // Combine airline and flight number to get FlightId
        $trimmedFlightNumber = preg_match('/^0/', $flightNumber)
        ? (ltrim($flightNumber, '0') !== '' ? preg_replace('/^0/', '', $flightNumber, 1) : '0')
        : $flightNumber;
        
        $flightId = $airline . $trimmedFlightNumber;

        // Format date for database query
        $formattedDate = Carbon::parse($flightDate)->format('Y-m-d');

        // First check if there's a record in TagRecheck
        $query = TagRecheck::where('FlightId', $flightId)
            ->whereDate('ScheduledDatetime', $formattedDate);

        // Add seat number filter if provided (handle leading zeros)
        if ($seatNumber) {
            // Remove leading zeros from the seat number
            $trimmedSeatNumber = ltrim(preg_replace('/^0+(?=\d)/', '', $seatNumber), '0');
            
            // Match against the database using the trimmed seat number
            $query->where(function($q) use ($seatNumber, $trimmedSeatNumber) {
                $q->where('SeatNumber', $seatNumber)
                  ->orWhere('SeatNumber', $trimmedSeatNumber)
                  ->orWhere('SeatNumber', 'LIKE', '%' . $trimmedSeatNumber);
            });
        }

        // Improved name matching logic - handle both name orders if both parts provided
        if ($lastName && $firstName) {
            $query->where(function($q) use ($lastName, $firstName) {
                // Try both name orders
                $name1 = $lastName . ' ' . $firstName;
                $name2 = $firstName . ' ' . $lastName;
                
                $q->where('NamePassenger', 'LIKE', '%' . $name1 . '%')
                  ->orWhere('NamePassenger', 'LIKE', '%' . $name2 . '%')
                  // Also look for individual name parts in any order
                  ->orWhere(function($subq) use ($lastName, $firstName) {
                      $subq->where('NamePassenger', 'LIKE', '%' . $lastName . '%')
                           ->where('NamePassenger', 'LIKE', '%' . $firstName . '%');
                  });
            });
        } elseif ($lastName) {
            // If only one name part is provided
            $query->where('NamePassenger', 'LIKE', '%' . $lastName . '%');
        } elseif ($firstName) {
            $query->where('NamePassenger', 'LIKE', '%' . $firstName . '%');
        }
       
        $tagRecheckData = $query->first();
        // dd($tagRecheckData);
        if ($tagRecheckData) {
            // Extract counter letter from CounterDetail
            $recheckCounter = null;
            if ($tagRecheckData['CounterDetail']) {
                // Extract the letter after the dash for formats like "T2H-C06"
                if (preg_match('/^[^-]*-([A-Za-z])/', explode(',', $tagRecheckData['CounterDetail'])[0], $matches)) {
                    $recheckCounter = $matches[1];
                } else {
                    // Fallback: extract first letter if no dash found
                    $recheckCounter = preg_match('/^([A-Za-z])/', $tagRecheckData['CounterDetail'], $matches) ? $matches[1] : $tagRecheckData['CounterDetail'];
                }
            }
            
            // Define messages in different languages
            $messages = [
                'EN' => 'Please proceed to the recheck counter at ' . $recheckCounter,
                'VI' => 'Vui lòng đến quầy kiểm tra lại tại ' . $recheckCounter,
                'KO' => $recheckCounter . ' 재확인 카운터로 이동하십시오',
                'ZH' => '请前往' . $recheckCounter . '重新检查柜台'
            ];
            
            // Get message in requested language or fallback to English
            $message = $messages[$languageCode] ?? $messages['EN'];
            
            return response()->json([
                'success' => true,
                'Recheck' => 'Yes',
                'data' => $tagRecheckData,
                'Passenger' => $tagRecheckData['NamePassenger'],
                'Counter' => $recheckCounter,
                'message' => $message
            ]);
        }

        // If not found in TagRecheck, check DepartureMovementView
        $startDate = Carbon::parse($formattedDate)->setTime(0, 0, 0);
        $endDate = Carbon::parse($formattedDate)->setTime(23, 59, 59);

        $departureData = DepartureMovementView::where('FlightId', $flightId)
            ->where('ScheduledDatetime', '>=', $startDate)
            ->where('ScheduledDatetime', '<=', $endDate)
            ->where('Status', '<>', '3')
            ->first();

        if ($departureData) {
            // Extract counter letter from CounterDetail
            $departureCounter = null;
            if ($departureData['CounterDetail']) {
                // Extract the letter after the dash for formats like "T2H-C06"
                if (preg_match('/^[^-]*-([A-Za-z])/', explode(',', $departureData['CounterDetail'])[0], $matches)) {
                    $departureCounter = $matches[1];
                } else {
                    // Fallback: extract first letter if no dash found
                    $departureCounter = preg_match('/^([A-Za-z])/', $departureData['CounterDetail'], $matches) ? $matches[1] : $departureData['CounterDetail'];
                }
            }
            $gate = preg_match('/^[^-]*-(.*)$/', explode(',', $departureData['Gate'])[0], $matches) ? $matches[1] : $departureData['Gate'];
            $lift = '';
            if ($gate == 'G01' || $gate == 'G02' || $gate == 'G03') {
                $lift = 'L1.2.3';
            } elseif ($gate == 'G08' || $gate == 'G09' || $gate == 'G10') {
                $lift = 'L8.9.10';
            } elseif ($gate == 'G04' || $gate == 'G05') {
                $lift = 'L4.5';
            } elseif ($gate == 'G06' || $gate == 'G07') {
                $lift = 'L6.7';
            }

            // Define messages for "No recheck needed" in different languages
            $noRecheckMessages = [
                'EN' => 'No baggage recheck required. Please proceed to departure gate.',
                'VI' => 'Không cần kiểm tra lại hành lý. Vui lòng đi đến cổng khởi hành.',
                'KO' => '수하물 재확인이 필요하지 않습니다. 출발 게이트로 이동하십시오.',
                'ZH' => '无需重新检查行李。请前往登机口。'
            ];
            
            // Get message in requested language or fallback to English
            $message = $noRecheckMessages[$languageCode] ?? $noRecheckMessages['EN'];
            
            return response()->json([
                'success' => true,
                'Recheck' => 'No',
                'data' => $departureData,
                'Counter' => $departureCounter,
                'Gate' => $gate,
                'Lift' => $lift,
                'message' => $message
            ]);
        }

        // If no data found in either source
        $notFoundMessages = [
            'EN' => 'No matching records found for the provided barcode data',
            'VI' => 'Không tìm thấy dữ liệu phù hợp với mã vạch được cung cấp',
            'KO' => '제공된 바코드 데이터에 대한 일치하는 기록을 찾을 수 없습니다',
            'ZH' => '找不到与提供的条形码数据相匹配的记录'
        ];
        
        $message = $notFoundMessages[$languageCode] ?? $notFoundMessages['EN'];
        
        return response()->json([
            'success' => false,
            'message' => $message
        ], 404);
    }

    /**
     * @OA\Get(
     *     path="/recheck-statistics/{date}",
     *     summary="Get recheck statistics by date",
     *     description="Returns statistics for baggage recheck operations on a specific date",
     *     tags={"Barcode Processing"},
     *     @OA\Parameter(
     *         name="date",
     *         in="path",
     *         required=true,
     *         description="Date in Y-m-d format",
     *         @OA\Schema(type="string", format="date", example="2025-03-29")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statistics retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="date", type="string", example="2025-03-29"),
     *                 @OA\Property(property="total_recheck", type="integer", example=45),
     *                 @OA\Property(property="finished_recheck", type="integer", example=42),
     *                 @OA\Property(property="pending_recheck", type="integer", example=3),
     *                 @OA\Property(property="completion_rate", type="number", example=93.33)
     *             )
     *         )
     *     )
     * )
     */
    public function getRecheckStatistics($selectedDate)
    {
        try {
            $startDate = Carbon::parse($selectedDate)->setTime(0, 0, 0);
            $endDate = Carbon::parse($selectedDate)->setTime(23, 59, 59);

            $totalRecheck = TagRecheck::where('ScheduledDatetime', '>=', $startDate)
                ->where('ScheduledDatetime', '<=', $endDate)
                ->where('IsDelete', 0)
                ->count();

            $finishedRecheck = TagRecheck::where('ScheduledDatetime', '>=', $startDate)
                ->where('ScheduledDatetime', '<=', $endDate)
                ->where('IsDelete', 0)
                ->where('Status', 4)
                ->count();

            $pendingRecheck = $totalRecheck - $finishedRecheck;

            return response()->json([
                'success' => true,
                'data' => [
                    'date' => $selectedDate,
                    'total_recheck' => $totalRecheck,
                    'finished_recheck' => $finishedRecheck,
                    'pending_recheck' => $pendingRecheck,
                    'completion_rate' => $totalRecheck > 0 ? round(($finishedRecheck / $totalRecheck) * 100, 2) : 0
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/recheck-by-counter/{date}/{counter}",
     *     summary="Get recheck records by counter and date",
     *     description="Returns all recheck records for a specific counter on a specific date",
     *     tags={"Barcode Processing"},
     *     @OA\Parameter(
     *         name="date",
     *         in="path",
     *         required=true,
     *         description="Date in Y-m-d format",
     *         @OA\Schema(type="string", format="date", example="2025-03-29")
     *     ),
     *     @OA\Parameter(
     *         name="counter",
     *         in="path",
     *         required=true,
     *         description="Counter letter (e.g., H, C)",
     *         @OA\Schema(type="string", example="H")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Records retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="count", type="integer", example=10)
     *         )
     *     )
     * )
     */
    public function getRecheckByCounter($selectedDate, $counter)
    {
        try {
            $startDate = Carbon::parse($selectedDate)->setTime(0, 0, 0);
            $endDate = Carbon::parse($selectedDate)->setTime(23, 59, 59);

            $records = TagRecheck::where('ScheduledDatetime', '>=', $startDate)
                ->where('ScheduledDatetime', '<=', $endDate)
                ->where('CounterDetail', 'LIKE', '%' . $counter . '%')
                ->where('IsDelete', 0)
                ->orderBy('ScheduledDatetime', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $records,
                'count' => $records->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving recheck records: ' . $e->getMessage()
            ], 500);
        }
    }
}
