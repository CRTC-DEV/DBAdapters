<?php

namespace App\Http\Livewire\T2Location;

use App\Models\T2Location;
use Livewire\Component;

class T2LocationDetailLive extends Component
{
    public $message;
    public $t2_location;
    public $t2_location_id;
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
            // 'map_item.CadId.required' => __('zzz'),
            // 'map_item.CadId.numeric' => __('zzz'),
        ];
    }

    public function mount($id)
    {
        $this->t2_location_id = $id;
        $obj_t2_location = new T2Location();
        $this->t2_location = $obj_t2_location->getT2LocationById($id);
        // dd($this->map_item);
    }

    public function render()
    {
        return view('livewire.t2-location.t2_location_edit');
    }

    public function save(){
        // dd($this->map_item);
        $this->validate();
        
        $obj_t2_location = new T2Location();
        // $obj_map_item->insertMapItem($this->map_item);
        $obj_t2_location->updateT2Location($this->t2_location, $this->t2_location_id);
        // $this->t2_location->save();
        
        return redirect()->route('t2-location')->with(['message' => __('Updated Successfull'), 'status' => 'success']);

    }

    public function delete()
    {   
        $this->t2_location->Status = DELETED_FLG;
        $obj_t2_location = new T2Location();
        $obj_t2_location->deleteT2Location(
            ['Status' => 3 ], $this->t2_location_id);
        
        return redirect()->route('t2-location')->with(['message' => __('Deleted Succesfull'), 'status' => 'success']);
    }
}