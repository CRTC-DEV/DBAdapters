<?php

namespace App\Http\Livewire\ItemType;
use App\Models\ItemType;
use Livewire\Component;

class ItemTypeDetailLive extends Component
{

    public $message;
    public $item_type;
    public $item_type_id;
    public function rules()
    {
        return [
            'item_type.Name' => 'required|string',
            'item_type.Description' => 'required',
            'item_type.Status' => 'required|numeric',  
            'item_type.IsShow' => 'required|numeric'
         
         

           
        ];
    }

    public function messages()
    {
        return [
            // 'item_type.CadId.required' => __('zzz'),
            // 'item_type.CadId.numeric' => __('zzz'),
        ];
    }

    public function mount($id)
    {
        $this->item_type_id = $id;
        $obj_item_type = new ItemType();
        $this->item_type = $obj_item_type->getItemTypeById($id);
        // dd($this->item_type);
    }

    public function render()
    {
        return view('livewire.item-type.item_type_edit');
    }

    public function save(){

        $this->validate();
        // dd($this->item_type);
        $obj_item_type = new ItemType();
        $obj_item_type->updateItemType($this->item_type, $this->item_type_id);

        // $this->item_type->save();
        
        return redirect()->route('item-type')->with(['message' => __('be_msg.add_complete'), 'status' => 'success']);

    }

    public function delete()
    {   
        $this->item_type->Status = DELETED_FLG;
        $obj_item_type = new ItemType();
        $obj_item_type->deleteItemType(
           $this->item_type_id);
        
        return redirect()->route('item-type')->with(['message' => __('be_msg.delete_success'), 'status' => 'success']);
    }
}
