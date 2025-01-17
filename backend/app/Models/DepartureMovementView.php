<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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
    //dd($data);
    return $data;
    }
}
