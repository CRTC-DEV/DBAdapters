<?php

namespace App\Http\Livewire\MapItem;

use App\Models\ItemDescription;
use App\Models\ItemTitle;
use App\Models\ItemType;
use App\Models\MapItem;
use App\Models\T2Location;
use Livewire\Component;

class MapItemAddLive extends Component
{

    public $message;
    //public $map_item;
    public $map_item = ['Status' => 2,'Rank' =>1];
    public $t2location;
    public $item_title;
    public $description;
    public $itemtype;

    public function rules()
    {
        return [
            // 'map_item.TextContentId' => 'required|numeric',
            'map_item.CadId' => 'required',
            'map_item.KeySearch' => 'required',
            'map_item.Status' => 'required|numeric',
            'map_item.T2LocationId' => 'required|numeric',
            'map_item.TitleId' => 'required|numeric',
            'map_item.DescriptionId' => 'required|numeric',
            'map_item.ItemTypeId' => 'required|numeric',

            // 'map_item.UserId' => 'required|numeric',
            'map_item.Longitudes' => 'required',
            'map_item.Latitudes' => 'required',  
            'map_item.Rank' => 'required|numeric', 
          

            
        ];
    }

    public function messages()
    {
        return [
        ];
    }

    public function mount()
    {
        
        // dd($this->item_title);
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
        // dd($this->description,$this->t2location, $this->item_title);

        return view('livewire.map-item.map_item_add');
    }

    public function save(){
        
        $this->validate();

        $obj_map_item = new MapItem();
        $obj_map_item->insertMapItem($this->map_item);

        return redirect()->route('map-item')->with(['message' => __('be_msg.add_complete'), 'status' => 'success']);

    }
}