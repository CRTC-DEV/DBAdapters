<?
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ArrivalMovement;

class ArrivalLive extends Component
{
    public $arrivals = [];
    public $selectedArrivalId = null;

    public function mount()
    {
        $this->fetchArrivals();
    }

    public function fetchArrivals()
    {
        // Lấy dữ liệu chuyến bay đến từ bảng `arrivals`
        $this->arrivals = ArrivalMovement::orderBy('arrival_time', 'asc')->get();
    }

    public function toggleDetails($arrivalId)
    {
        // Nếu đang chọn chuyến bay này, bỏ chọn (tắt thông tin chi tiết)
        $this->selectedArrivalId = $this->selectedArrivalId === $arrivalId ? null : $arrivalId;
    }

    public function render()
    {
        return view('livewire.arrival-live');
    }
}
