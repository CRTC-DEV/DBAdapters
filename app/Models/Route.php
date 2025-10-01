<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Route extends Model
{
    protected $table = 'Route';
    protected $primaryKey = 'Id';

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
     * Get all routes
     */
    public function getAllRoutes()
    {
        return self::orderBy('Name', 'asc')->get();
    }

    /**
     * Insert new route
     */
    public function insertRoute($data)
    {
        return self::create($data);
    }

    /**
     * Update route by Id
     */
    public function updateRoute($data, $id)
    {
        return self::where('Id', $id)->update($data);
    }

    /**
     * Get route by name
     */
    public function getRouteByName($name)
    {
        return self::where('Name', $name)->first();
    }

    /**
     * Get routes by city
     */
    public function getRoutesByCity($city)
    {
        return self::where('CityName', 'like', '%' . $city . '%')->get();
    }
}
