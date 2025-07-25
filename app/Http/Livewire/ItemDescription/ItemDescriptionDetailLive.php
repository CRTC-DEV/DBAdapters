<?php

namespace App\Http\Livewire\ItemDescription;

use App\Models\ItemDescription;
use App\Models\TextContent;
use Livewire\Component;
//use 
class ItemDescriptionDetailLive extends Component
{

    public $message;
    public $item_description;
    public $item_description_id;
    public $text_content;
    public function rules()
    {
        return [
            'item_description.TextContentId' => 'required|numeric',
            // 'item_description.KeySearch' => 'required',
            'item_description.Status' => 'required|numeric',
            
        ];
    }

    public function messages()
    {
        return [
            // 'item_description.CadId.required' => __('zzz'),
            // 'item_description.CadId.numeric' => __('zzz'),
        ];
    }

    public function mount($id)
    {
        $this->item_description_id = $id;
        $obj_item_description = new ItemDescription();
        $this->item_description = $obj_item_description->getItemById($id);
        // dd( $this->item_description);
    }

    public function render()
    {
        $obj_text_content = new TextContent();
        $this->text_content = $obj_text_content->getAllTextContent(); 
        return view('livewire.item-description.item_description_edit');
    }

    public function save(){
        // dd($this->item_description);
        $this->validate();
        
        $obj_item_description = new ItemDescription();
        // $obj_item_description->insertMapItem($this->item_description);
        $obj_item_description->updateItem($this->item_description, $this->item_description_id);
        
        return redirect()->route('item-description')->with(['message' => __('Insert Successfull'), 'status' => 'success']);

    }

    public function delete()
    {   
        $this->item_description->Status = DELETED_FLG;
        $obj_item_description = new ItemDescription();
        $obj_item_description->deleteItem( $this->item_description_id);
        
        return redirect()->route('item-description')->with(['message' => __('Deleted Successfull'), 'status' => 'success']);
    }
}
