<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Airlines extends Model
{
    protected $table = 'Airlines';
    protected $primaryKey = 'AirlineId';

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
     * Get all active airlines
     */
    public function getActiveAirlines()
    {
        return self::
            orderBy('Name', 'asc')
            ->get();
    }

    /**
     * Get all inactive airlines
     */
    public function searchAirlines($keyword)
    {
        return self::where('Status', 2)
            ->where(function($query) use ($keyword) {
                $query->where('Name', 'like', "%{$keyword}%")
                      ->orWhere('IcaoCode', 'like', "%{$keyword}%")
                      ->orWhere('IataCode', 'like', "%{$keyword}%");
            })
            ->orderBy('Name', 'asc')
            ->get();
    }

    /**
     * Insert new airline
     */
    public function insertAirline($data)
    {
        return self::create($data);
    }

    /**
     * Update airline by AirlineId
     */
    public function updateAirline($data, $airlineId)
    {
        return self::where('AirlineId', $airlineId)->update($data);
    }

    /**
     * Get airline by IATA code
     */
    public function getAirlineByIataCode($iataCode)
    {
        return self::where('IataCode', $iataCode)->first();
    }

    /**
     * Get airline by ICAO code
     */
    public function getAirlineByIcaoCode($icaoCode)
    {
        return self::where('IcaoCode', $icaoCode)->first();
    }
}
