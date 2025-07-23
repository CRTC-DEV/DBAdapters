<?php

namespace App\Http\Livewire\Languages;

use App\Models\Languages;
use Livewire\Component;

class LanguagesDetailLive extends Component
{
    public $message;
    public $languages;
    public $languages_id;
    public function rules()
    {
        return [
        //    'languages.Zone' => 'required|numeric',
        //     'languages.Floor' => 'required|numeric',
            'languages.Status' => 'required|numeric',
            'languages.Name' => 'required',
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
        $this->languages_id = $id;
        $obj_languages = new Languages();
        $this->languages = $obj_languages->getLanguageById($id);
        // dd($this->map_item);
    }

    public function render()
    {
        return view('livewire.languages.languages_edit');
    }

    public function save(){
        // dd($this->map_item);
        $this->validate();
        
        $obj_languages = new Languages();
        // $obj_map_item->insertMapItem($this->map_item);
        $obj_languages->updateLanguage($this->languages, $this->languages_id);
        // $this->languages->save();
        
        return redirect()->route('languages')->with(['message' => __('Updated Successfull'), 'status' => 'success']);

    }

    public function delete()
    {   
        $this->languages->Status = DELETED_FLG;
        $obj_languages = new Languages();
        $obj_languages->deleteLanguage(
            ['Status' => 3 ], $this->languages_id);
        
        return redirect()->route('languages')->with(['message' => __('Deleted Succesfull'), 'status' => 'success']);
    }
}