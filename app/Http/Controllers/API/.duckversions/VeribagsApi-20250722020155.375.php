<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TagRecheck;
use Carbon\Carbon; // Add this line to import Carbon
use App\Models\DepartureMovementView;
use Illuminate\Http\Request;

class VeribagsApi   extends Controller
{
    //
    public function getAllTagRecheckApi()
    {

        $data = (new TagRecheck)->getAllTagRecheck();
        return response()->json($data);
    }

    public function getTagRecheckApi($tagnumber)
    {
        $data = (new TagRecheck)->getTagRecheckByTagNumber($tagnumber);
        return response()->json($data);
    }

    public function processBarcodeData(Request $request)
    {
        // Get parameters from query string
        $airline = $request->query('airline');
        $flightNumber = $request->query('flightNumber');
        $flightDate = $request->query('flightDate');
        $seatNumber = $request->query('seatNumber');
        $lastName = $request->query('lastName');
        $firstName = $request->query('firstName');
        $languageCode = $request->query('languageCode', 'EN');

        // Validate required parameters
        if (!$airline || !$flightNumber || !$flightDate) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required parameters: airline, flightNumber, flightDate'
            ], 400);
        }

        // Combine airline and flight number to get FlightId
        $flightId = $airline . $flightNumber;

        // Format date for database query (adjust format if necessary)
        $formattedDate = Carbon::parse($flightDate)->format('Y-m-d');

        // First check if there's a record in TagRecheck
        $query = TagRecheck::where('FlightId', $flightId)
            ->whereDate('ScheduledDatetime', $formattedDate);

        // Add seat number filter if provided
        if ($seatNumber) {
            // Remove leading zeros from the seat number
            $trimmedSeatNumber = ltrim(preg_replace('/^0+(?=\d)/', '', $seatNumber), '0');
            
            // Match against the database using the trimmed seat number
            $query->where(function($q) use ($seatNumber, $trimmedSeatNumber) {
                $q->where('SeatNumber', $seatNumber)
                  ->orWhere('SeatNumber', $trimmedSeatNumber)
                  // Also match if database has leading zeros but input doesn't
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
        
        // Get the message in the requested language, fallback to English if not available
        if ($tagRecheckData) {
             $recheckCounter = preg_match('/^([A-Za-z])/', explode(',', $tagRecheckData['CounterDetail'])[0], $matches) ? $matches[1] : $tagRecheckData['CounterDetail'];

            $messages = [
                'EN' => 'Please proceed to the recheck counter at ' . $recheckCounter,
                'VN' => 'Vui lòng đến quầy kiểm tra lại tại đảo ' . $recheckCounter,
                'KR' => $recheckCounter . ' 재확인 카운터로 이동하십시오',
                'CN' => '请前往' . $recheckCounter . '重新检查柜台'
            ];
            $message = $messages[$languageCode] ?? $messages['EN'];

            return response()->json([
                'success' => true,
                'Recheck' => 'Yes',
                // 'data' => $tagRecheckData,
                'Passenger' => $tagRecheckData['NamePassenger'],
                'Counter' => $recheckCounter,
                'message' => $message
            ]);
        }

        // If not found in TagRecheck, check DepartureMovementView
        $startDate = Carbon::parse($formattedDate)->setTime(4, 0, 0);
        $endDate = Carbon::parse($formattedDate)->addDay()->setTime(3, 59, 59);

        $departureData = DepartureMovementView::where('FlightId', $flightId)
            ->where('ScheduledDatetime', '>=', $startDate)
            ->where('ScheduledDatetime', '<=', $endDate)
            ->first();

            
        if ($departureData) {
            $departureCounter = preg_match('/^[^-]*-([A-Za-z])/', explode(',', $departureData['CounterDetail'])[0], $matches) ? $matches[1] : $departureData['CounterDetail'];

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
            
            return response()->json([
                'success' => true,
                'Recheck' => 'No',
                'data' => $departureData,
                'Counter' => $departureCounter,
                'Gate' => $gate,
                'Lift' => $lift,
            ]);
        }

        // If no data found in either source
        return response()->json([
            'success' => false,
            'message' => 'No matching records found for the provided barcode data'
        ], 404);
    }
}
