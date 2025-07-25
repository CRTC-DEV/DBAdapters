<?php

namespace App\Http\Livewire\API;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\GroupSearchMapItem;


Class GroupSearchMapItemAPI extends Component
{
    // public items;

    public function getAllGroupSearchMapItem(){
        $obj_items = new GroupSearchMapItem();
        $items = $obj_items->getAllItems();
        
        return response()->json($items);
    }

    public function getGroupSearchMapItemById($id){
        $obj_items = new GroupSearchMapItem();
        $items = $obj_items->getItemById($id);
        
        return response()->json($items);
    }

    public function getGroupSearchMapItemAPI($keysearch){        

        $obj_items = new GroupSearchMapItem();
        $items = $obj_items->getGroupSearchMapItemAPI($keysearch);
        
        return response()->json($items);
    }

    public function getGroupSearchMapItem_All(){
        $obj_items = new GroupSearchMapItem();
        $items = $obj_items->getAllItems();
        
        return response()->json($items);
    }

    //for POST API
    public function getGroupSearchMapItemFromBody(Request $request)
{
    // get data from body


    $json = $request->json()->all();
    // save data to a json file
    $jsonFile = '/home/www/html/dbadapter/IN/data.json';
    if (!file_exists($jsonFile)) {
        return response()->json([
            'status' => 'error',
            'message' => 'File path does not exist'
        ], 404);
    }
    file_put_contents($jsonFile, json_encode($json, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
    $catId = $request->input('catid');
    $languageid = $request->input('languageid');
    $floor = $request->input('floor');

        
    // get data from header
    $apiKey = $request->header('X-API-Key');
    
    // check api key
    // if (!$apiKey) {
    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'API key is required'
    //     ], 401);
    // }
    
    $obj_items = new GroupSearchMapItem();
    $items = $obj_items->getGroupSearchMapItemAPI($catId, $languageid);   
    $t= $catId.'-'.$languageid;
    return response()->json([
        'status' => 'success',
        'data' => $items,
        'test' => $t
    ]);
}




}
