<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class DepartureMovement extends Model
{
    protected $table = 'DepartureMovement';
    
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
        $this->fillable = array_diff($columns, ['Id','created_at', 'updated_at']);
    }

    /**
     * Update an Departure movement record by MovementId
     *
     * @param array $data
     * @param int $movementId
     * @return int
     */
    public function updateDepartureMovement($data, $movementId)
    {
        return self::where('MovementId', $movementId)->update($data);
    }

    function insertDepartureMovement($data){
        // dd($data);              
        // dd($data);
        return DepartureMovement::insertGetId($data);
    }
}
