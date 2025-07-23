<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ArrivalMovementView extends Model
{
   
    protected $table = 'ArrivalMovement_View';

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

    function getArrivalMovementAPI($startDate, $endDate)

    {
        $data =  ArrivalMovementView::where('ArrivalMovement_View.ScheduledDatetime', '>=', $startDate)
        ->where('ArrivalMovement_View.ScheduledDatetime', '<=', $endDate)       
        ->select('ArrivalMovement_View.*')
        ->orderBy('ArrivalMovement_View.ScheduledDatetime', 'asc')
        ->get();
    //dd($data);
    return $data;
    }

    function getArrivalWebAPI($startDate, $endDate)

    {
        $data =  ArrivalMovementView::where('ArrivalMovement_View.ScheduledDatetime', '>=', $startDate)
        ->where('ArrivalMovement_View.ScheduledDatetime', '<=', $endDate)   
        ->where('ArrivalMovement_View.Status', '<>', '3')    
        ->select('ArrivalMovement_View.*')
        ->orderBy('ArrivalMovement_View.ScheduledDatetime', 'asc')
        ->get();
    //dd($data);
    return $data;
    }


}
