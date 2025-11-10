<?php
namespace App\Http\Livewire\GroupSearchMapItem;

use App\Models\GroupSearchMapItem;
use App\Models\GroupSearch;
use App\Models\ItemTitle;
use Livewire\Component;

class GroupSearchMapItemAdd extends Component
{

    public $message;
    public $group_search_map_item = ['Status' => 2];
    public $item_title;

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

    public function mount()
    {
        //use model ItemTitle for view
        //$this->item_title = ItemTitle::where('Status', '!=', DELETED_FLG)->get();
        //$this->group_search_map_item = GroupSearchMapItem::all();
        //$this->group_search_map_item = GroupSearchMapItem::all(); // Load item types for dropdown
        
    }

    public function render()
    {
        $obj_group_search = new GroupSearch();
        $this->group_search = $obj_group_search->getAllItems();
        return view('livewire.group-search-map-item.group_search_map_item_add');
    }

    public function save(){
        
        $this->validate();

        $obj_group_search = new GroupSearchMapItem();
        $obj_group_search->insertItem($this->group_search_map_item);

        return redirect()->route('group-search-map-item')->with(['message' => __('be_msg.add_complete'), 'status' => 'success']);

    }
}
