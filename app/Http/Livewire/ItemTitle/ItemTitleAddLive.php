<?php

namespace App\Http\Livewire\ItemTitle;

use App\Models\ItemTitle;
use App\Models\MapItem;
use App\Models\TextContent;
use Livewire\Component;

class ItemTitleAddLive extends Component
{

    public $message;
    public $item_title = ['Status' => 2,'IsShow'=>1];   
    public $textcontent;

    public function rules()
    {
        return [
            'item_title.TextContentId' => 'required|numeric',
            'item_title.Status' => 'required|numeric'
            
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
        $this->textcontent = $obj_text_content->getAllTextContent();
        
        return view('livewire.item-title.item_title_add');
    }

    public function save(){
        
        $this->validate();
        //dd($this->item_title);
        $obj_item_title = new ItemTitle();
        $obj_item_title->insertItem($this->item_title);
        
        return redirect()->route('item-title')->with(['message' => __('Insert Succesful'), 'status' => 'success']);

    }
}
