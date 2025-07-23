<?php

namespace App\Http\Livewire\API;

use Livewire\Component;
use App\Models\ItemTitle;

class ItemTitleAPI extends Component
{
    // public items;

    public function getAllItemTitle(){
        $obj_items = new ItemTitle();
        $items = $obj_items->getAllItems();
        
        return response()->json($items);
    }
}
