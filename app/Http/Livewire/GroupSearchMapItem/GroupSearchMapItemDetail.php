<?php

namespace App\Http\Livewire\GroupSearchMapItem;

use App\Models\GroupSearchMapItem;
use App\Models\GroupSearch;
use App\Models\MapItem;

use Livewire\Component;

class GroupSearchMapItemDetail extends Component
{

    public $message;
    public $group_search_map_item;
    public $group_search_map_item_id;
    public $group_search;
    public $map_item;
    
    public function rules()
    {
        return [
            'group_search_map_item.MapItemId' => 'required|numeric',         
            'group_search_map_item.GroupSearchId' => 'required|numeric',
            'group_search_map_item.Status' => 'required|numeric',           
            'group_search_map_item.Priority' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            // 'group_search.CadId.required' => __('zzz'),
            // 'group_search.CadId.numeric' => __('zzz'),
        ];
    }

    public function mount($id)
    {
        
        [$this->group_search_id, $this->map_item_id] = explode(",", $id);
        $obj_group_search_map_item = new GroupSearchMapItem();
        $this->group_search_map_item = $obj_group_search_map_item->getItemById($this->group_search_id, $this->map_item_id);

       
        //dd($this->group_search_map_item);
    }

    public function render()
    {
      
        $obj_group_search = new GroupSearch();
        $this->group_search = $obj_group_search->getAllItems();
        $obj_map_item = new MapItem();
        $this->map_item = $obj_map_item->getAllMapItems();
        return view('livewire.group-search-map-item.group_search_map_item_edit');
    }

    public function save(){

        $this->validate();
        // dd($this->group_search);
        $obj_group_search = new GroupSearchMapItem();
        $obj_group_search->updateItem($this->group_search_map_item, $this->group_search_id, $this->map_item_id);

        // $this->group_search->save();
        
        return redirect()->route('group-search-map-item')->with(['message' => __('be_msg.add_complete'), 'status' => 'success']);

    }

    public function delete()
    {   
       
        $obj_group_search = new GroupSearchMapItem();
        $obj_group_search->deleteItem($this->map_item_id, $this->group_search_id);        
        return redirect()->route('group-search-map-item')->with(['message' => __('be_msg.delete_success'), 'status' => 'success']);
    }
}
