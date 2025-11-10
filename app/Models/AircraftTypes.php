<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AircraftTypes extends Model
{
    protected $table = 'AircraftTypes';
    protected $primaryKey = 'AircraftTypeId';

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
     * Get all active aircraft types
     */
    public function getActiveAircraftTypes()
    {
        return self::where('Status', 1)
            ->orderBy('Name', 'asc')
            ->get();
    }

    /**
     * Insert new aircraft type
     */
    public function insertAircraftType($data)
    {
        return self::create($data);
    }

    /**
     * Update aircraft type by AircraftTypeId
     */
    public function updateAircraftType($data, $aircraftTypeId)
    {
        return self::where('AircraftTypeId', $aircraftTypeId)->update($data);
    }

    /**
     * Get aircraft type by IATA code
     */
    public function getAircraftTypeByIataCode($iataCode)
    {
        return self::where('IataCode', $iataCode)->first();
    }

    /**
     * Get aircraft type by ICAO code
     */
    public function getAircraftTypeByIcaoCode($icaoCode)
    {
        return self::where('IcaoCode', $icaoCode)->first();
    }
}
