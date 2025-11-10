<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DepartureMovementView extends Model
{
   
    protected $table = 'DepartureMovement_View';

    // Disable automatic timestamps
    public $timestamps = false;

    // Dynamically set the fillable columns
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Ensure $columns is always an array
        $columns = Schema::hasTable($this->getTable())
            ? Schema::getColumnListing($this->getTable())
            : [];

        // Populate the fillable property dynamically
        $this->fillable = array_diff($columns, ['created_at', 'updated_at']);
    }
//App API
    function getDepartureMovementAPI($startDate, $endDate)

    {
        $data =  DepartureMovementView::where('DepartureMovement_View.ScheduledDatetime', '>=', $startDate)
        ->where('DepartureMovement_View.ScheduledDatetime', '<=', $endDate)  
        ->where('DepartureMovement_View.Status', '<>', '3')       
        ->select('DepartureMovement_View.*')
        ->orderBy('DepartureMovement_View.ScheduledDatetime', 'asc')
        ->get();
    //dd($data);
    return $data;
    }

//Web API
    function getDepartureWebAPI($startDate, $endDate)

    {
        $data =  DepartureMovementView::where('DepartureMovement_View.ScheduledDatetime', '>=', $startDate)
        ->where('DepartureMovement_View.ScheduledDatetime', '<=', $endDate)   
        ->where('DepartureMovement_View.Status', '<>', '3')    
        ->select('DepartureMovement_View.*')
        ->orderBy('DepartureMovement_View.ScheduledDatetime', 'asc')
        ->get();
    //dd($data);
    return $data;
    }

    function getDepartureMovementCheckinAPI($startDate, $endDate, $checkin)

    {
        $data =  DepartureMovementView::where('DepartureMovement_View.ScheduledDatetime', '>=', $startDate)
        ->where('DepartureMovement_View.ScheduledDatetime', '<=', $endDate) 
        ->where('DepartureMovement_View.GateStatus', '<>', 'Gate Closed') 
        ->whereRaw('SUBSTRING(DepartureMovement_View.CounterDetail, 3, 1) = ?', [$checkin])
        ->select('DepartureMovement_View.*')
        ->orderBy('DepartureMovement_View.ScheduledDatetime', 'asc')
        ->get();
    // dd($data);
        return $data;
    }

    function getDepartureMovementCheckinTimeAPI2($startDate, $endDate, $checkin)
{
    $currentTime = now();
    $threeHoursAhead = $currentTime->addHours(3);

    $data = DepartureMovementView::where('DepartureMovement_View.ScheduledDatetime', '>=', $startDate)
        ->where('DepartureMovement_View.ScheduledDatetime', '<=', $endDate)
        ->where('DepartureMovement_View.GateStatus', '<>', 'Gate Closed')
        ->whereRaw('SUBSTRING(DepartureMovement_View.CounterDetail, 3, 1) = ?', [$checkin])
        ->where('DepartureMovement_View.ScheduledDatetime', '<=', $threeHoursAhead)
        ->select('DepartureMovement_View.*')
        ->orderBy('DepartureMovement_View.ScheduledDatetime', 'asc')
        ->get();

    // dd($data);
    return $data;
}

function getDepartureMovementCheckinTimeAPI($startDate, $endDate, $checkin)
{
    $currentTime = now('Asia/Ho_Chi_Minh');
    $threeHoursLater = $currentTime->copy()->addHours(3);

    $data = DepartureMovementView::where('DepartureMovement_View.ScheduledDatetime', '>=', $startDate)
        ->where('DepartureMovement_View.ScheduledDatetime', '<=', $endDate)
        ->where('DepartureMovement_View.GateStatus', '<>', 'Gate Closed')
        ->whereRaw('SUBSTRING(DepartureMovement_View.CounterDetail, 3, 1) = ?', [$checkin])
        ->where(function ($query) use ($currentTime, $threeHoursLater) {
            $query->where(function ($q) use ($currentTime, $threeHoursLater) {
                $q->whereRaw("YEAR(DepartureMovement_View.EstimatedTime) != 1753")
                  ->where('DepartureMovement_View.EstimatedTime', '>', $currentTime)
                  ->where('DepartureMovement_View.EstimatedTime', '<=', $threeHoursLater);
            })->orWhere(function ($q) use ($currentTime, $threeHoursLater) {
                $q->whereRaw("YEAR(DepartureMovement_View.EstimatedTime) = 1753")
                  ->where('DepartureMovement_View.ScheduledDatetime', '>', $currentTime)
                  ->where('DepartureMovement_View.ScheduledDatetime', '<=', $threeHoursLater);
            });
        })
        ->select('DepartureMovement_View.*')
        ->orderBy('DepartureMovement_View.ScheduledDatetime', 'asc')
        ->get();

    // dd($data);
    return $data;
}


    function getAirlineNameAndLogoAPI($startDate, $endDate, $checkin) // Keep the $checkin variable name
    {
        // Build the query
        $query = DepartureMovementView::query()
            ->where('DepartureMovement_View.ScheduledDatetime', '>=', $startDate)
            ->where('DepartureMovement_View.ScheduledDatetime', '<=', $endDate)
            ->where('DepartureMovement_View.GateStatus', '<>', 'Gate Closed');

        $checkinValues = explode(',', $checkin);
        $checkinValues = array_map('trim', $checkinValues);

        if (is_array($checkinValues) && !empty($checkinValues)) {
            $placeholders = implode(', ', array_fill(0, count($checkinValues), '?'));
            $query->whereRaw('SUBSTRING(DepartureMovement_View.CounterDetail, 3, 1) IN (' . $placeholders . ')', $checkinValues);
        }

        $query->select('DepartureMovement_View.FlightId', 'DepartureMovement_View.Airline');
        $query->distinct();
        // $query->orderBy('DepartureMovement_View.ScheduledDatetime', 'asc');

        $data = $query->get();

        $result = $data->map(function ($item) {
            $flightIdPrefix = Str::substr($item->FlightId, 0, 2);
            $logoUrl = 'https://camranh.aero/flight-movement/airlines_logo/' . $flightIdPrefix . '.jpg';

            return [
                'name' => $item->Airline,
                'logo' => $logoUrl,
            ];
        });
        // Remove duplicate items
        $result = array_unique(array_map("serialize", $result->toArray()), SORT_STRING);
        $result = array_map("unserialize", $result);

        return $result;
    }
}
