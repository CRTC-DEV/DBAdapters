<?php

namespace App\Http\Livewire\RouteMapItemDetail;

use App\Models\RouteMapItemDetail;
use App\Models\RouteMapItem;
use Livewire\Component;

class RouteMapItemDetailAddLive extends Component
{

    public $message;
    public $route_map_item_detail;
    public $route_map_item;
    // [ 'RouteMapItemId', 'NameIndex', 'Status', 'CreatedDate', 'ModifiDate', 'UserId', 'Longitudes','Latitudes','OrderIndex'];
    public function rules()
    {
        return [
            'route_map_item_detail.RouteMapItemId' => 'required|numeric',
            'route_map_item_detail.NameIndex' => 'required',
            'route_map_item_detail.Status' => 'required|numeric',
            'route_map_item_detail.Longitudes' => 'required',
            'route_map_item_detail.Latitudes' => 'required',
            'route_map_item_detail.OrderIndex' => 'required|numeric',
            // 'route_map_item_detail.Status' => 'required|numeric',
            // 'route_map_item_detail.Status' => 'required|numeric',

        ];
    }

    public function messages()
    {
        return [
    
        ];
    }

    public function mount()
    {
        // $obj_route_map_item = new RouteMapItem();
        // $this->route_map_item = $obj_route_map_item->getAllRouteMapItem();
    }

    public function render()
    {
        $obj_route_map_item = new RouteMapItem();
        $this->route_map_item = $obj_route_map_item->getAllRouteMapItem();
        // dd($this->route_map_item);
        return view('livewire.route-map-item-detail.route_map_item_detail_add');
    }

    public function save(){
        
        $this->validate();
        // dd($this->item_title);
        $obj_route_map_item_detail = new RouteMapItemDetail();
        $obj_route_map_item_detail->insertRouteMapItemDetail($this->route_map_item_detail);
        
        return redirect()->route('route-map-item-detail')->with(['message' => __('Insert Successfull'), 'status' => 'success']);

    }
}
