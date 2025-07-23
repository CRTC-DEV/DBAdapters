<?php
namespace App\Http\Livewire\GroupSearch;

use App\Models\GroupSearch;
use App\Models\ItemTitle;
use Livewire\Component;

class GroupSearchAddLive extends Component
{

    public $message;
    public $group_search = ['Status' => 2];
    public $item_title;

    public function rules()
    {
        return [
            'group_search.Name' => 'required|string',         
            'group_search.KeySearch' => 'required|string',
            'group_search.Priority' => 'required|numeric',
            'group_search.Description' => 'required|string',
            'group_search.TitleId' => 'required|numeric',
            'group_search.Status' => 'required|numeric',
            'group_search.Rank' => 'required|numeric',
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
        //use only for load page, not refresh data
        //$this->item_title = ItemTitle::where('Status', '!=', DELETED_FLG)->get();
        
        
    }

    public function render()
    {
        //for refresh data
        $obj_item_title = new ItemTitle();
        $this->item_title = $obj_item_title->getAllItems();
       
        return view('livewire.group-search.group_search_add');
    }

    public function save(){
        
        $this->validate();

        $obj_group_search = new GroupSearch();
        $obj_group_search->insertItem($this->group_search);

        return redirect()->route('group-search')->with(['message' => __('be_msg.add_complete'), 'status' => 'success']);

    }
}
