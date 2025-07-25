<?php

namespace App\Http\Livewire\Translations;

use Livewire\Component;
use App\Models\Translations;
// use App\Models\TextContent;
// use App\Models\Languages;
class TranslationsListLive extends Component
{
    public $translations;
    public function mount(){
        // $obj_item = new ItemTitle();
        $obj_translations= new Translations();
        $this->translations = $obj_translations->getAllTranslations();
        // dd($this->translations);
    }
    public function render()
    {   
        return view('livewire.translations.translations_list');
    }
}
