<?php

namespace App\Http\Livewire\RouteMapItem;

use App\Models\RouteMapItem;
use App\Models\MapItem;
use Livewire\Component;

class RouteMapItemDetailLive extends Component
{
    public $message;
    public $route_map_item;
    public $route_map_item_id;
    public $map_item;
    public function rules()
    {
        return [
           'route_map_item.StartMapItemId' => 'required',
            'route_map_item.EndMapItemId' => 'required|numeric',
            'route_map_item.Status' => 'required|numeric',
            'route_map_item.Name' => 'required',

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
        $this->route_map_item_id = $id;
        $obj_route_map_item = new RouteMapItem();
        $this->route_map_item = $obj_route_map_item->getRouteMapItemById($id);
        // dd($this->map_item);
    }

    public function render()
    {
        $obj_map_item = new MapItem();
        $this->map_item = $obj_map_item->getAllMapItems();
        return view('livewire.route-map-item.route_map_item_edit');
    }

    public function save(){
        // dd($this->map_item);
        $this->validate();
        
        $obj_route_map_item = new RouteMapItem();
        // $obj_map_item->insertMapItem($this->map_item);
        $obj_route_map_item->updateRouteMapItem($this->route_map_item, $this->route_map_item_id);
        // $this->route_map_item->save();
        
        return redirect()->route('route-map-item')->with(['message' => __('Updated Successfull'), 'status' => 'success']);

    }

    public function delete()
    {   
        $this->route_map_item->Status = DELETED_FLG;
        $obj_route_map_item = new RouteMapItem();
        $obj_route_map_item->deleteRouteMapItem(
            ['Status' => 3 ], $this->route_map_item_id);
        
        return redirect()->route('route-map-item')->with(['message' => __('Deleted Succesfull'), 'status' => 'success']);
    }
}