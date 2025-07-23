<?php

namespace App\Http\Livewire\RouteMapItem;

use App\Models\RouteMapItem;
use Livewire\Component;

class RouteMapItemListLive extends Component
{
    public $group_search;
    public function mount(){
        // $obj_item = new ItemTitle();
        $obj_group_search= new RouteMapItem();
        $this->group_search = $obj_group_search->getAllRouteMapItem();
        // dd($this->map_item);
    }
    public function render()
    {   
        return view('livewire.route-map-item.route_map_item_list');
    }
}
