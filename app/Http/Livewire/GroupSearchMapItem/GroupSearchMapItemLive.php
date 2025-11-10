<?php

namespace App\Http\Livewire\GroupSearchMapItem;

use App\Models\GroupSearchMapItem;
use Livewire\Component;

class GroupSearchMapItemLive extends Component
{
    public $group_search_map_item;
   
    public function mount(){
        // $obj_item = new ItemDescription();
        $obj_group_searchs= new GroupSearchMapItem();
        $this->group_search_map_item = $obj_group_searchs->getAllItems();
        // dd($this->item_type);
    }
    public function render()
    {
        return view('livewire.group-search-map-item.group_search_map_item');
    }
}
