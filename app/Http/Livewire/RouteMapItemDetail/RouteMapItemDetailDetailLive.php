<?php

namespace App\Http\Livewire\RouteMapItemDetail;

use App\Models\RouteMapItemDetail;
use App\Models\RouteMapItem;
use Livewire\Component;

class RouteMapItemDetailDetailLive extends Component
{
    public $message;
    public $route_map_item_detail;
    public $route_map_item_id;
    public $longitudes;
    public $latitudes;
    public $route_map_item;
    public function rules()
    {
        return [
           'route_map_item_detail.RouteMapItemId' => 'required|numeric',
            'route_map_item_detail.NameIndex' => 'required',
            'route_map_item_detail.Status' => 'required|numeric',
            'route_map_item_detail.Longitudes' => 'required',
            'route_map_item_detail.Latitudes' => 'required',
            'route_map_item_detail.OrderIndex' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            // 'map_item.CadId.required' => __('zzz'),
            // 'map_item.CadId.numeric' => __('zzz'),
        ];
    }

    public function mount($id)
    {
        // $this->route_map_item_detail_id = $id;
        [$this->route_map_item_id, $this->longitudes, $this->latitudes] = explode(",", $id);

        $obj_route_map_item_detail = new RouteMapItemDetail();
        $this->route_map_item_detail = $obj_route_map_item_detail->getRouteMapItemDetailById($this->route_map_item_id, $this->longitudes, $this->latitudes);
        // dd($this->map_item);
    }

    public function render()
    {
        $obj_route_map_item = new RouteMapItem();
        $this->route_map_item = $obj_route_map_item->getAllRouteMapItem();
        return view('livewire.route-map-item-detail.route_map_item_detail_edit');
    }

    public function save(){
        // dd($this->map_item);
        $this->validate();
        
        $obj_route_map_item_detail = new RouteMapItemDetail();
        // $obj_map_item->insertMapItem($this->map_item);
        $obj_route_map_item_detail->updateRouteMapItemDetail($this->route_map_item_detail, $this->route_map_item_id, $this->longitudes, $this->latitudes);
        // $this->route_map_item_detail->save();
        
        return redirect()->route('route-map-item-detail')->with(['message' => __('Updated Successfull'), 'status' => 'success']);

    }

    public function delete()
    {   
        $this->route_map_item_detail->Status = DELETED_FLG;
        $obj_route_map_item_detail = new RouteMapItemDetail();
        $obj_route_map_item_detail->deleteRouteMapItemDetail(
            ['Status' => 3 ], $this->route_map_item_detail_id);
        
        return redirect()->route('map-item')->with(['message' => __('Deleted Succesfull'), 'status' => 'success']);
    }
}