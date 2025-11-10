<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Airports extends Model
{
    protected $table = 'Airports';
    protected $primaryKey = 'AirportId';

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
     * Get all active airports
     */
    public function getActiveAirports()
    {
        return self::where('Status', 1)
            ->orderBy('Name', 'asc')
            ->get();
    }

    /**
     * Insert new airport
     */
    public function insertAirport($data)
    {
        return self::create($data);
    }

    /**
     * Update airport by AirportId
     */
    public function updateAirport($data, $airportId)
    {
        return self::where('AirportId', $airportId)->update($data);
    }

    /**
     * Get airport by IATA code
     */
    public function getAirportByIataCode($iataCode)
    {
        return self::where('IataCode', $iataCode)->first();
    }

    /**
     * Get airport by ICAO code
     */
    public function getAirportByIcaoCode($icaoCode)
    {
        return self::where('IcaoCode', $icaoCode)->first();
    }

    /**
     * Get airports by city
     */
    public function getAirportsByCity($city)
    {
        return self::where('City', 'like', '%' . $city . '%')
            ->where('Status', 1)
            ->get();
    }
}
