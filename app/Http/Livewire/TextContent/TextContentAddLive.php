<?php

namespace App\Http\Livewire\TextContent;

use App\Models\TextContent;
use Livewire\Component;

class TextContentAddLive extends Component
{

    public $message;
    public $text_content;

    public function rules()
    {
        return [
            'text_content.OriginalText' => 'required',
            'text_content.OriginalLanguageId' => 'required|numeric',
            'text_content.Status' => 'required|numeric',

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
        return view('livewire.text-content.text_content_add');
    }

    public function save(){
        
        $this->validate();
        // dd($this->item_title);
        $obj_text_content = new TextContent();
        $obj_text_content->insertTextContent($this->text_content);
        
        return redirect()->route('text-content')->with(['message' => __('Insert Successfull'), 'status' => 'success']);

    }
}
