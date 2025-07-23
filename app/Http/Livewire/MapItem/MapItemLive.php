<?php

namespace App\Http\Livewire\MapItem;

use App\Models\ItemTitle;
use App\Models\MapItem;
use Livewire\Component;

class MapItemLive extends Component
{
    public $map_item;
    public function mount(){
        // $obj_item = new ItemTitle();
        $obj_map_items= new MapItem();
        $this->map_item = $obj_map_items->getAllMapItems();
        // dd($this->map_item);
    }
    public function render()
    {
        return view('livewire.map-item.map_item');
    }
}
