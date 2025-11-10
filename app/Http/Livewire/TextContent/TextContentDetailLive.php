<?php

namespace App\Http\Livewire\TextContent;

use App\Models\TextContent;
use Livewire\Component;

class TextContentDetailLive extends Component
{
    public $message;
    public $text_content;
    public $text_content_id;
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
            // 'map_item.CadId.required' => __('zzz'),
            // 'map_item.CadId.numeric' => __('zzz'),
        ];
    }

    public function mount($id)
    {
        $this->text_content_id = $id;
        $obj_text_content = new TextContent();
        $this->text_content = $obj_text_content->getTextContentById($id);
        // dd($this->map_item);
    }

    public function render()
    {
        return view('livewire.text-content.text_content_edit');
    }

    public function save(){
        // dd($this->map_item);
        $this->validate();
        
        $obj_text_content = new TextContent();
        // $obj_map_item->insertMapItem($this->map_item);
        $obj_text_content->updateTextContent($this->text_content, $this->text_content_id);
        // $this->text_content->save();
        
        return redirect()->route('text-content')->with(['message' => __('Updated Successfull'), 'status' => 'success']);

    }

    public function delete()
    {   
        $this->text_content->Status = DELETED_FLG;
        $obj_text_content = new TextContent();
        $obj_text_content->deleteTextContent(
            ['Status' => 3 ], $this->text_content_id);
        
        return redirect()->route('text-content')->with(['message' => __('Deleted Succesfull'), 'status' => 'success']);
    }
}