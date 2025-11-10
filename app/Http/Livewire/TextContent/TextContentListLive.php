<?php

namespace App\Http\Livewire\TextContent;

use App\Models\TextContent;
use Livewire\Component;

class TextContentListLive extends Component
{
    public $text_content;
    public function mount(){
        // $obj_item = new ItemTitle();
        $obj_text_content= new TextContent();
        $this->text_content = $obj_text_content->getAllTextContent();
        // dd($this->map_item);
    }
    public function render()
    {   
        return view('livewire.text-content.text_content_list');
    }
}
