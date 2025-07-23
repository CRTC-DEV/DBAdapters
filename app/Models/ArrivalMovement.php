<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ArrivalMovement extends Model
{
    protected $table = 'ArrivalMovement';

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

    /**
     * Update an arrival movement record by MovementId
     *
     * @param array $data
     * @param int $movementId
     * @return int
     */
    
    public function updateMovement($data, $movementId)
    {
        return self::where('MovementId', $movementId)        
        ->update($data);
    }

    public function insertMovement($data,$movementId)
    {
        // Kiểm tra ID trùng lặp trước khi insert
        $info='';
        if (isset($movementId)) {
            //update 30/12 check duplication new rule with FlightId and ScheduledDatetime
            $existingRecord = ArrivalMovement::where('MovementId', $movementId)           
            ->first();
            if ($existingRecord) {
                // Nếu ID đã tồn tại, trả về thông báo và update flight
                ArrivalMovement::updateMovement($data, $movementId);
                $info='Update ArrivalMovement successfully';
                
            } else {
                // Insert nếu không bị trùng
                $newId = ArrivalMovement::insertGetId($data);
                $info='Insert ArrivalMovement successfully';
                
            }
        }
        return $info;
    }
}
