<?php

namespace App\Http\Livewire\T2Location;

use App\Models\T2Location;
use Livewire\Component;

class T2LocationAddLive extends Component
{

    public $message;
    public $t2_location= ['Status' => 2];

    public function rules()
    {
        return [
            't2_location.Zone' => 'required|numeric',
            't2_location.Floor' => 'required|numeric',
            't2_location.Status' => 'required|numeric',
            't2_location.Name' => 'required',

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
        return view('livewire.t2-location.t2_location_add');
    }

    public function save(){
        
        $this->validate();
        // dd($this->item_title);
        $obj_t2_location = new T2Location();
        $obj_t2_location->insertT2Location($this->t2_location);
        
        return redirect()->route('t2-location')->with(['message' => __('Insert Successfull'), 'status' => 'success']);

    }
}
