<?php

namespace App\Http\Livewire\Translations;

use Livewire\Component;
use App\Models\Languages;
use App\Models\TextContent;
use App\Models\Translations;

class TranslationsDetailLive extends Component
{
    public $message;
    public $translations;
    public $translations_textcontent_id;
    public $translations_language_id;
    
    // public $languages;
    // public $text_content;
    public function rules()
    {
        return [
           'translations.TextContentId' => 'required',
            'translations.LanguageId' => 'required|numeric',
            'translations.Translation' => 'required',
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
        // $this->translations_id = $id;

        [$this->translations_textcontent_id, $this->translations_language_id] = explode(",", $id);

        $obj_translations = new Translations();
        $this->translations = $obj_translations->getTranslationsById($this->translations_textcontent_id, $this->translations_language_id);
        
    }

    public function render()
    {
        return view('livewire.translations.translations_edit');
    }

    public function save(){
        // dd($this->map_item);
        $this->validate();
        $obj_translations = new Translations();
        $obj_translations->updateTranslations($this->translations, $this->translations_textcontent_id, $this->translations_language_id);
        // $this->translations->save();
        
        return redirect()->route('translations')->with(['message' => __('Updated Successfull'), 'status' => 'success']);

    }

    // public function delete()
    // {   
    //     $this->translations->Status = DELETED_FLG;
    //     $obj_translations = new Translations();
    //     $obj_translations->deleteTranslations(
    //         ['Status' => 3 ], $this->translations_textcontent_id, $this->translations_language_id);
        
    //     return redirect()->route('translations')->with(['message' => __('Deleted Succesfull'), 'status' => 'success']);
    // }
}