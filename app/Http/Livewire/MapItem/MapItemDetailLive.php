<?php

namespace App\Http\Livewire\MapItem;

use App\Models\ItemTitle;
use App\Models\MapItem;
use App\Models\ItemType;
use App\Models\ItemDescription;
use App\Models\T2Location;
use Livewire\Component;

class MapItemDetailLive extends Component
{

    public $message;
    public $map_item;
    public $map_item_id;
    public $t2location;
    public $item_title;
    public $description;
    public $itemtype;
    public function rules()
    {
        return [
            'map_item.CadId' => 'required',
            'map_item.KeySearch' => 'required',
            'map_item.Status' => 'required|numeric',
            'map_item.T2LocationId' => 'required|numeric',
            'map_item.TitleId' => 'required|numeric',
            'map_item.DescriptionId' => 'required|numeric',
            'map_item.ItemTypeId' => 'required|numeric',
            'map_item.Rank' => 'required|numeric',
            'map_item.AreaSide' => 'required|numeric',
            'map_item.UserId' => 'required|numeric',
            'map_item.Longitudes' => 'required',
            'map_item.Latitudes' => 'required',

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
        $this->map_item_id = $id;
        $obj_map_item = new MapItem();
        $this->map_item = $obj_map_item->getMapItemById($id);
        // dd($this->map_item);
    }

    public function render()
    {
        $obj_t2location = new T2Location();
        $obj_title = new ItemTitle();
        $obj_description = new ItemDescription();

        $this->t2location = $obj_t2location->getAllT2Location();
        $this->item_title = $obj_title->getAllItemsWithTextContent();
        $this->description = $obj_description->getAllItemsDescriptionWithTextContent();
        $obj_itemtype = new ItemType();
        $this->itemtype = $obj_itemtype->getAllItemTypes();
        return view('livewire.map-item.map_item_edit');
    }

    public function save(){
        // dd($this->map_item);
        $this->validate();
        
        $obj_map_item = new MapItem();
        // $obj_map_item->insertMapItem($this->map_item);
        $obj_map_item->updateMapItem($this->map_item, $this->map_item_id);
        // $this->map_item->save();
        
        return redirect()->route('map-item')->with(['message' => __('be_msg.add_complete'), 'status' => 'success']);

    }

    public function delete()
    {   
        $this->map_item->Status = DELETED_FLG;
        $obj_map_item = new MapItem();
        $obj_map_item->deleteMapItem(
            ['Status' => 3 ], $this->map_item_id);
        
        return redirect()->route('map-item')->with(['message' => __('be_msg.delete_success'), 'status' => 'success']);
    }
}
