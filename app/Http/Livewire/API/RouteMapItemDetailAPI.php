<?php

namespace App\Http\Livewire\API;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\RouteMapItemDetail;


Class RouteMapItemDetailAPI extends Component
{
    // public items;

    public function getRouteMapItemDetailAPI($id){        

              
        $obj_items = new RouteMapItemDetail();
        $items = $obj_items->getRouteMapItemDetailAPI($id);
        
        return response()->json($items);
    }

    public function getRouteMapItemDetail_All(){
        $obj_items = new RouteMapItemDetail();
        $items = $obj_items->getRouteMapItemDetail();
        
        return response()->json($items);
    }



    
    
}





