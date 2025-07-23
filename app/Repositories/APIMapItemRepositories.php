<?php

/**
 * Date 2018-09-10
 */

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\MapItem;

class APIMapItemRepositories
{
    public function getAllMapItems(){
        $obj_map_item = new MapItem();
        $data = $obj_map_item->getAllMapItems();
        
        return $data;
    }
}
