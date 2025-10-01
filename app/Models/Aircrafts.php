<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Aircrafts extends Model
{
    protected $table = 'Aircrafts';
    protected $primaryKey = 'AircraftId';

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
     * Get all active aircrafts
     */
    public function getActiveAircrafts()
    {
        return self::where('Status', 1)
            ->orderBy('Registration', 'asc')
            ->get();
    }

    /**
     * Insert new aircraft
     */
    public function insertAircraft($data)
    {
        return self::create($data);
    }

    /**
     * Update aircraft by AircraftId
     */
    public function updateAircraft($data, $aircraftId)
    {
        return self::where('AircraftId', $aircraftId)->update($data);
    }

    /**
     * Get aircraft by registration
     */
    public function getAircraftByRegistration($registration)
    {
        return self::where('Registration', $registration)->first();
    }
}
