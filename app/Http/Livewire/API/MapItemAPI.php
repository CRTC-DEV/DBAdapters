<?php

namespace App\Http\Livewire\API;
use Illuminate\Http\Request;
use Livewire\Component;
//use App\Repositories\APIMapItem;
use App\Models\MapItem;

class MapItemAPI extends Component
{
    // public items;

    public function getMapItemFromBody(Request $request)
    {
// get data from body
        $floor = $request->input('floor');    
// get data from header
    $apiKey = $request->header('X-API-Key');
// check api key
// if (!$apiKey) {
//     return response()->json([
//         'status' => 'error',
//         'message' => 'API key is required'
//     ], 401);
    
        $obj_items = new MapItem();
        //$items = $obj_items->getItemDescriptionAPI($catId, $languageid,$floor);
        $items = $obj_items->getAllMapItemsAPI($floor);
//dd($items);
    
    return response()->json([
    'status' => 'success',
    'data' => $items
        ]);
    }

    public function getMapItem($floor)
    {

   
        $obj_items = new MapItem();
        //$items = $obj_items->getItemDescriptionAPI($catId, $languageid,$floor);
        $items = $obj_items->getAllMapItemsAPI($floor);
//dd($items);
    
    return response()->json([
    'status' => 'success',
    'data' => $items
        ]);
    }

}



