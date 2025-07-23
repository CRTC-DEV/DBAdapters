<?php

namespace App\Http\Livewire\Translations;

use App\Models\Languages;
use App\Models\TextContent;
use App\Models\Translations;
use Livewire\Component;

class TranslationsAddLive extends Component
{

    public $message;
    public $translations;
    public $languages;
    public $text_content;
    public function rules()
    {
        return [
            'translations.TextContentId' => 'required|numeric',
            'translations.LanguageId' => 'required|numeric',
            'translations.Translation' => 'required',

        ];
    }

    public function messages()
    {
        return [
    
        ];
    }

    public function mount()
    {
        
        // dd(   $this->languages, $this->text_content);
    }

    public function render()
    {
        $obj_text_content = new TextContent();
        $obj_languages = new Languages();
        $this->languages = $obj_languages->getAllLanguages();
        $this->text_content = $obj_text_content->getAllTextContent();
        //dd($this->text_content);
        return view('livewire.translations.translations_add');
    }

    public function save(){
        
        $this->validate();
        // dd($this->item_title);
        $obj_translations = new Translations();
        $obj_translations->insertTranslations($this->translations);
        
        return redirect()->route('translations')->with(['message' => __('Insert Successfull'), 'status' => 'success']);

    }
}
