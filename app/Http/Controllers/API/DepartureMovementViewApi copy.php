<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DepartureMovementView;
use Carbon\Carbon; // Add this line to import Carbon

use Illuminate\Http\Request;

class DepartureMovementViewApi extends Controller
{
    //API for App
    public function getDepartureMovement($selectedDate){

        $startDate = Carbon::parse($selectedDate)->setTime(4, 0, 0);
        $endDate = Carbon::parse($selectedDate)->addDay()->setTime(3, 59, 59);        
        //dd($startDate,$endDate);
        
        $obj = new DepartureMovementView();
        $items = $obj->getDepartureMovementAPI($startDate, $endDate);        
        return response()->json($items);//As data array
        
    }

    //API for Web
    public function getDepartureWeb($byDate){

        $startDate = Carbon::parse($byDate)->setTime(0, 0, 0);
        $endDate = Carbon::parse($byDate)->setTime(23, 59, 59);        
        //dd($startDate,$endDate);
        
        $obj = new DepartureMovementView();
        $items = $obj->getDepartureWebAPI($startDate, $endDate);        
        return response()->json($items);//As data array
        
    }


    //API for filter checkin
    public function getDepartureMovementByCheckin($selectedDate,$checkin){

        $startDate = Carbon::parse($selectedDate)->setTime(4, 0, 0);
        $endDate = Carbon::parse($selectedDate)->addDay()->setTime(3, 59, 59);        
        //dd($startDate,$endDate);
        // dd(1);
        $obj = new DepartureMovementView();
        $items = $obj->getDepartureMovementCheckinAPI($startDate, $endDate, $checkin);        
        return response()->json($items);//As data array
        
    }
    //API Led
    public function getAirlineNameAndLogo($selectedDate,$checkin){

        $startDate = Carbon::parse($selectedDate)->setTime(4, 0, 0);
        $endDate = Carbon::parse($selectedDate)->addDay()->setTime(3, 59, 59);  
        
        $obj = new DepartureMovementView();
        $items = $obj->getAirlineNameAndLogoAPI($startDate, $endDate, $checkin);        
        return response()->json($items);//As data array
    }
}
