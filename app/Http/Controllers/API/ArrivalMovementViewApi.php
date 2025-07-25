<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ArrivalMovementView;
use Carbon\Carbon; // Add this line to import Carbon

use Illuminate\Http\Request;

class ArrivalMovementViewApi extends Controller
{
    //
    public function getArrivalMovement($selectedDate){

        $startDate = Carbon::parse($selectedDate)->setTime(4, 0, 0);
        $endDate = Carbon::parse($selectedDate)->addDay()->setTime(3, 59, 59);        
        //dd($startDate,$endDate);
        
        $obj = new ArrivalMovementView();
        $items = $obj->getArrivalMovementAPI($startDate, $endDate);        
        return response()->json($items);//As data array
        
    }

     //API for Web
     public function getArrivalWeb($byDate){

        $startDate = Carbon::parse($byDate)->setTime(0, 0, 0);
        $endDate = Carbon::parse($byDate)->setTime(23, 59, 59);        
        //dd($startDate,$endDate);
        
        $obj = new ArrivalMovementView();
        $items = $obj->getArrivalWebAPI($startDate, $endDate);        
        return response()->json($items);//As data array
        
    }
}
