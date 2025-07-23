<?php

namespace App\Http\Livewire\GroupSearch;

use App\Models\GroupSearch;
use Livewire\Component;
use App\Models\ItemTitle;

class GroupSearchDetailLive extends Component
{

    public $message;
    public $group_search;
    public $group_search_id;
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

    public function mount($id)
    {
        $this->group_search_id = $id;
        $obj_group_search = new GroupSearch();
        $this->group_search = $obj_group_search->getItemById($id);
       
        //dd($this->group_search);
    }

    public function render()
    { 
        $obj_item_title = new ItemTitle();
        $this->item_title = $obj_item_title->getAllItems();
        return view('livewire.group-search.group_search_edit');
    }

    public function save(){

        $this->validate();
        // dd($this->group_search);
        $obj_group_search = new GroupSearch();
        $obj_group_search->updateItem($this->group_search, $this->group_search_id);

        // $this->group_search->save();
        
        return redirect()->route('group-search')->with(['message' => __('be_msg.add_complete'), 'status' => 'success']);

    }

    public function delete()
    {   
       
        $obj_group_search = new GroupSearch();
        $obj_group_search->deleteItem($this->group_search_id);        
        return redirect()->route('group-search')->with(['message' => __('be_msg.delete_success'), 'status' => 'success']);
    }
}
