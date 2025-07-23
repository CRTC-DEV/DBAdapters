<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CollapseMenu extends Component
{

    public $isCollapsed = true; // Trạng thái mặc định là đóng (collapsed)
    public function mount()
    {
        $this->isCollapsed = session()->get('isCollapsed', true); // Lấy trạng thái từ session
    }

    public function toggleCollapse()
    {
        $this->isCollapsed = !$this->isCollapsed;
        session()->put('isCollapsed', $this->isCollapsed); // Lưu trạng thái vào session
    }
    public function render()
    {
        return view('livewire.collapse-menu');
    }
}
