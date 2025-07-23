<?php

namespace App\Http\Livewire\ItemDescription;

use App\Models\ItemDescription;
use App\Models\RouteMapItem;
use App\Models\TextContent;
use Livewire\Component;

class ItemDescriptionAddLive extends Component
{

    public $message;
    public $item_description = ['Status' => 2];   
    public $text_content;

    public function rules()
    {
        return [
            'item_description.TextContentId' => 'required|numeric',
            'item_description.Status' => 'required|numeric',

        ];
    }

    public function messages()
    {
        return [
        ];
    }

    public function mount()
    {

    }

    public function render()
    {
        $obj_text_content = new TextContent();
        $this->text_content = $obj_text_content->getAllTextContent(); 
 
        return view('livewire.item-description.item_description_add');
    }

    public function save(){
        
        $this->validate();
        //dd($this->item_description);
        $obj_item_description = new ItemDescription();
        $obj_item_description->insertItem($this->item_description);
        
        return redirect()->route('item-description')->with(['message' => __('be_msg.add_complete'), 'status' => 'success']);

    }
}
